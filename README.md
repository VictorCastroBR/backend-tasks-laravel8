# 🖥️ Backend – Tasks App (Laravel 8)

API REST para gerenciamento de tarefas com autenticação JWT, multiempresa e exportação assíncrona de dados.

---

## 📋 Funcionalidades

- Autenticação JWT
- Multi-Tenant (Global Scope)
- CRUD de tarefas com filtros
- CRUD de usuários da empresa (somente admin)
- Exportação de tarefas para CSV (fila)
- Envio de e-mail com link seguro para download
- Controle de acesso via Policies

---

## 🚀 Tecnologias

- PHP 7.4  
- Laravel 8  
- JWT Auth  
- Queues (database driver)  
- Mail  
- MySQL  

---

## ⚙️ Instalação (Sem Docker)

```bash
git clone 
cd backend
cp .env.example .env

# Configurar .env (DB, JWT_SECRET, MAIL, etc.)
composer install
php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed

php artisan serve
```

Segue um exemplo já juntando tudo no seu `README.md` e adaptado para o seu `docker-compose.yml`, incluindo os passos que você realmente vai precisar:


## 🐳 Instalação (Com Docker)

```bash
git clone https://github.com/VictorCastroBR/backend-tasks-laravel8.git
cd backend backend-tasks-laravel8

docker compose up -d --build
````

## ⚙️ Configuração inicial

Após subir os containers, execute os comandos abaixo **dentro do container PHP (`app`)**:

```bash
docker compose exec app composer install

docker compose exec app php artisan key:generate

docker compose exec app php artisan jwt:secret
```

> 💡 **Importante:**
>
> * Verifique se no arquivo `.env` o host do banco está configurado como `DB_HOST=db` e porta `3306`.
> * As credenciais padrão do MySQL no docker-compose são:
>
>   ```
>   DB_DATABASE=laravel
>   DB_USERNAME=laravel
>   DB_PASSWORD=laravel
>   ```

## 🌀 Filas (Queue)

```bash
docker compose exec app php artisan queue:table
docker compose exec app php artisan migrate
```

## 🕺🏽Criar usuário admin por comando

```bash
docker compose exec app php artisan app:bootstrap
```

## 🌐 Acesso

* Aplicação: [http://localhost:8081](http://localhost:8081)
