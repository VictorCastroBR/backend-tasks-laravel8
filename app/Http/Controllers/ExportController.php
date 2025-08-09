<?php

namespace App\Http\Controllers;

use App\Models\Export;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Services\Export\ExportService;
use App\Jobs\ExportTasksJob;

class ExportController extends Controller
{
    protected $service;

    public function __construct(ExportService $service)
    {
        $this->middleware('auth:api');
        $this->service = $service;
    }

    public function tasks(Request $request)
    {
        $filters = $request->only(['status', 'priority', 'search']);
        $export = $this->service->queueTasksExport($filters);

        dispatch(new ExportTasksJob($export->id));

        return response()->json([
            'message' => 'Export queued',
            'export' => [
                'id' => $export->id,
                'status' => $export->status
            ]
        ], 202);
    }

    public function show(Export $export)
    {
        $this->authorize('view', $export);

        $downloadUrl = null;
        if ($export->status === 'done' && $export->file_path) {
            $downloadUrl = route('exports.download', ['export' => $export->id]);
        }

        return response()->json([
            'id' => $export->id,
            'status' => $export->status,
            'created_at' => $export->created_at->toDateString(),
            'download_url' => $downloadUrl
        ]);
    }

    public function download(Export $export)
    {
        $this->authorize('download', $export);

        if ($export->status !== 'done' || empty($export->file_path) || !Storage::disk('local')->exists($export->file_path)) {
            return response()->json(['message' => 'Arquivo não disponível'], 404);
        }

        return Storage::disk('local')->download($export->file_path, 'tasks.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
