<?php

namespace App\Services\Export;

use App\Models\Export;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\ExportRepositoryInterface;
use Illuminate\Support\Str;

class ExportService
{
    protected $exports;

    public function __construct(ExportRepositoryInterface $exports)
    {
        $this->exports = $exports;
    }

    public function queueTasksExport(array $filters): Export
    {
        $user = Auth::user();

        return $this->exports->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'status' => 'queued',
            'filters' => $filters ?: []
        ]);
    }
}