<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        $companyName = trim($this->askRequired('Nome da empresa'));

        $companySlug = $this->makeUniqueSlug($companyName);

        $company = Company::create([
            'name' => $companyName,
            'slug' => $companySlug,
        ]);

        $this->info("Empresa '{$company->name}' criada com sucesso (slug: {$company->slug}).");

        $adminName = trim($this->askRequired('Nome do usuário admin'));

        $adminEmail = $this->askUniqueEmail();

        $password = $this->secretRequired('Senha do admin (não será exibida)');

        User::create([
            'company_id' => $company->id,
            'name'       => $adminName,
            'email'      => $adminEmail,
            'password'   => Hash::make($password),
            'is_admin'   => true,
        ]);

        $this->info("Usuário admin '{$adminName}' criado com sucesso.");
        return Command::SUCCESS;
    }

    protected function makeUniqueSlug(string $companyName): string
    {
        $base = Str::slug($companyName);
        $slug = $base;
        $i = 1;

        while (Company::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    protected function askRequired(string $question): string
    {
        do {
            $answer = (string) $this->ask($question);
            $answer = trim($answer);
            if ($answer !== '') {
                return $answer;
            }
            $this->warn('Este campo é obrigatório.');
        } while (true);
    }

    protected function secretRequired(string $question): string
    {
        do {
            $answer = (string) $this->secret($question);
            if (trim($answer) !== '') {
                return $answer;
            }
            $this->warn('A senha é obrigatória.');
        } while (true);
    }

    protected function askUniqueEmail(): string
    {
        do {
            $email = trim((string) $this->ask('E-mail do admin'));
            if ($email === '') {
                $this->warn('E-mail é obrigatório.');
                continue;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->warn('Formato de e-mail inválido.');
                continue;
            }
            if (User::where('email', $email)->exists()) {
                $this->warn('Este e-mail já está em uso. Informe outro.');
                continue;
            }
            return $email;
        } while (true);
    }
}
