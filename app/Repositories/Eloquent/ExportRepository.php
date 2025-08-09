<?php

namespace App\Repositories\Eloquent;

use App\Models\Export;
use App\Repositories\Contracts\ExportRepositoryInterface;

class ExportRepository implements ExportRepositoryInterface
{
    public function create(array $data): Export
    {
        return Export::create($data);
    }

    public function find($id): ?Export
    {
        return Export::find($id);
    }

    public function update(Export $export, array $data): Export
    {
        $export->update($data);
        return $export;
    }
}