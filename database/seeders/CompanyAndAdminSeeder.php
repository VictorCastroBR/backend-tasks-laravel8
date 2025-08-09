<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CompanyAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::create([
            'name' => 'Empresa Teste',
            'slug' => 'empresa-teste'
        ]);

        User::create([
            'company_id' => $company->id,
            'name' => 'Admin Teste',
            'email' => 'admin@teste.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true
        ]);
    }
}
