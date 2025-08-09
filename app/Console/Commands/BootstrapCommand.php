<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BootstrapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bootstrap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Criar a primeira empresa e usuário admin interativamente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('=== Configuração inicial do sistema ===');

        $companyName = $this->ask('Nome da empresa');
        $companySlug = $this->ask('Slug da empresa (ex: minha-empresa)');

        $company = Company::create([
            'name' => $companyName,
            'slug' => $companySlug
        ]);

        $this->info("Empresa '{$company->name}' criada com sucesso.");

        $adminName = $this->ask('Nome do usuário');
        $adminEmail = $this->ask('E-mail');
        $password = $this->ask('Senha do admin');

        User::create([
            'company_id' => $company->id,
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => Hash::make($password),
            'is_admin' => true
        ]);

        $this->info("Usuário admin '{$adminName}' criado com sucesso.");
        return Command::SUCCESS;
    }
}
