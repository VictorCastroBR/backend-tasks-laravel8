<?php

namespace App\Jobs;

use App\Models\Export;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Throwable;

class ExportTasksJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $exportId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $exportId)
    {
        $this->exportId = $exportId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TaskRepositoryInterface $tasksRepo)
    {
        $export = Export::find($this->exportId);
        if (!$export) { return; }

        $export->update(['status' => 'processing']);

        $filters   = $export->filters ?? [];
        $companyId = $export->company_id;

        $filename = 'tasks_'.$companyId.'_'.now()->format('Ymd_His').'.csv';
        $path     = 'exports/company_'.$companyId.'/'.$filename;

        $fullPath = \Storage::disk('local')->path($path);
        @mkdir(dirname($fullPath), 0775, true);

        $fp = fopen($fullPath, 'w');
        if (!$fp) {
            $export->update(['status' => 'failed']);
            throw new \RuntimeException('Não foi possível abrir o arquivo para escrita');
        }

        try {
            fputcsv($fp, ['ID','Título','Descrição','Status','Prioridade','Prazo','Concluída em','Autor','Criada em','Atualizada em']);

            $query = $tasksRepo->baseQueryForExport($filters, $companyId);

            $query->chunkById(1000, function ($tasks) use ($fp) {
                foreach ($tasks as $t) {
                    fputcsv($fp, [
                        $t->id,
                        $t->title,
                        $t->description,
                        $t->status,
                        $t->priority,
                        optional($t->due_date)->toDateString(),
                        optional($t->completed_at)->toDateTimeString(),
                        optional($t->user)->name,
                        $t->created_at->toDateTimeString(),
                        $t->updated_at->toDateTimeString(),
                    ]);
                }
            });

            fclose($fp);

            $export->update([
                'status'    => 'done',
                'file_path' => $path,
            ]);
        } catch (Throwable $e) {
            if (is_resource($fp)) { fclose($fp); }
            $export->update(['status' => 'failed']);
            throw $e;
        }
    }
}
