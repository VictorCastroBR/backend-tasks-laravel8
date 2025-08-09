<?php

namespace App\Repositories\Contracts;

use App\Models\Export;

interface ExportRepositoryInterface
{
    public function create(array $data): Export;
    public function find(int $id): ?Export;
    public function update(Export $export, array $data): Export;
}