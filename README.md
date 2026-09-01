# Sekai Portal

Sekai Portal is an online store for manga, cosplay costumes, accessories, and related merchandise.

The application is being developed as a modular Laravel monolith with a server-driven user interface built with Blade and Livewire.

## Technology Stack

### Backend

- PHP 8.5
- Laravel 13
- Livewire 4
- MySQL 8.4
- Redis

### Frontend

- Blade
- Livewire
- Alpine.js
- Tailwind CSS 4
- Vite 8

### Local Infrastructure

- Laravel Sail
- Docker
- Mailpit

## Requirements

The following tools are required for local development:

- Docker Desktop
- Git
- a Unix-compatible terminal

Local installations of PHP, Composer, Node.js, npm, MySQL, and Redis are not required. Project commands are executed inside Laravel Sail containers.

## Initial Setup

Clone the repository and enter the project directory:

```bash
git clone <repository-url>
cd sekaiportal
```

Create the local environment file:

```bash
cp .env.example .env
```

### Install Composer Dependencies

The `./vendor/bin/sail` command is unavailable until Composer dependencies have been installed.

If the `vendor` directory does not exist, bootstrap Composer through the official Laravel Sail Composer image:

```bash
docker run --rm \
    -v "$PWD:/var/www/html" \
    -w /var/www/html \
    laravelsail/php85-composer:latest \
    composer install
```

This is the only bootstrap command that does not use `./vendor/bin/sail`.

### Start the Containers

```bash
./vendor/bin/sail up -d
```

Verify that the services are running:

```bash
./vendor/bin/sail ps
```

### Generate the Application Key

```bash
./vendor/bin/sail artisan key:generate
```

### Run Database Migrations

```bash
./vendor/bin/sail artisan migrate
```

## Frontend Dependencies

The project's `node_modules` directory is stored in a dedicated Docker volume.

This prevents native npm packages installed for Linux from being mixed with packages installed for macOS or another host operating system.

After the Docker volume is created for the first time, assign it to the Sail user:

```bash
./vendor/bin/sail exec laravel.test chown -R sail:sail /var/www/html/node_modules
```

Install the exact dependencies recorded in `package-lock.json`:

```bash
./vendor/bin/sail npm ci
```

Verify the production build:

```bash
./vendor/bin/sail npm run build
```

## Development Workflow

Start the application infrastructure:

```bash
./vendor/bin/sail up -d
```

Start the Vite development server:

```bash
./vendor/bin/sail npm run dev
```

The Vite process runs in the foreground. Keep that terminal open while working on frontend code.

Stop the application infrastructure:

```bash
./vendor/bin/sail down
```

Do not add the `-v` option unless you intentionally want to delete the project's Docker volumes.

## Local URLs

With the default environment configuration:

- application: [http://localhost](http://localhost)
- Mailpit: [http://localhost:8025](http://localhost:8025)
- Vite development server: [http://localhost:5173](http://localhost:5173)

Ports can be overridden in the local `.env` file.

## Command Execution Policy

The project uses the PHP, Composer, Node.js, and npm versions provided by Laravel Sail.

Use:

```bash
./vendor/bin/sail artisan <command>
./vendor/bin/sail composer <command>
./vendor/bin/sail node <command>
./vendor/bin/sail npm <command>
```

Do not execute project commands directly through host-installed tools:

```bash
php artisan
composer
node
npm
```

Following this policy prevents version drift and avoids mixing platform-specific dependencies between the host operating system, Docker, and CI.

## Common Commands

### Laravel

Display information about the application:

```bash
./vendor/bin/sail artisan about
```

List routes:

```bash
./vendor/bin/sail artisan route:list
```

Display migration status:

```bash
./vendor/bin/sail artisan migrate:status
```

Run pending migrations:

```bash
./vendor/bin/sail artisan migrate
```

### Composer

Install dependencies from `composer.lock`:

```bash
./vendor/bin/sail composer install
```

Add a production dependency:

```bash
./vendor/bin/sail composer require vendor/package
```

Add a development dependency:

```bash
./vendor/bin/sail composer require --dev vendor/package
```

Check for known dependency vulnerabilities:

```bash
./vendor/bin/sail composer audit
```

### npm

Install dependencies from `package-lock.json`:

```bash
./vendor/bin/sail npm ci
```

Add a production dependency:

```bash
./vendor/bin/sail npm install package-name
```

Add a development dependency:

```bash
./vendor/bin/sail npm install --save-dev package-name
```

Start the Vite development server:

```bash
./vendor/bin/sail npm run dev
```

Create a production build:

```bash
./vendor/bin/sail npm run build
```

Check for known dependency vulnerabilities:

```bash
./vendor/bin/sail npm audit
```

## Quality Checks

Run the automated test suite:

```bash
./vendor/bin/sail artisan test
```

Check PHP formatting without modifying files:

```bash
./vendor/bin/sail composer exec pint -- --test
```

Automatically format PHP files:

```bash
./vendor/bin/sail composer exec pint
```

Check PHP dependencies for known vulnerabilities:

```bash
./vendor/bin/sail composer audit
```

Check frontend dependencies for known vulnerabilities:

```bash
./vendor/bin/sail npm audit
```

Verify the production frontend build:

```bash
./vendor/bin/sail npm run build
```

Before considering a change complete, run the relevant tests and quality checks for the affected area.

## Docker Services

The project uses the following services:

| Service | Purpose |
|---|---|
| `laravel.test` | Laravel application runtime |
| `mysql` | Primary relational database |
| `redis` | Cache and queue infrastructure |
| `mailpit` | Local email testing |
| `sail-node-modules` | Isolated frontend dependency storage |

Inspect the current service status:

```bash
./vendor/bin/sail ps
```

## Docker Volumes

Stopping the containers normally preserves local data:

```bash
./vendor/bin/sail down
```

Stopping the containers and deleting volumes removes local MySQL data, Redis data, and installed npm dependencies:

```bash
./vendor/bin/sail down -v
```

Use the `-v` option only when a complete local environment reset is intended.

After recreating the `sail-node-modules` volume, restore its ownership and reinstall frontend dependencies:

```bash
./vendor/bin/sail up -d
./vendor/bin/sail exec laravel.test chown -R sail:sail /var/www/html/node_modules
./vendor/bin/sail npm ci
```

## Dependency Updates

Avoid running unrestricted dependency updates without reviewing the proposed changes.

For Composer, inspect targeted changes first:

```bash
./vendor/bin/sail composer update vendor/package --with-all-dependencies --dry-run
```

Apply the targeted update only after reviewing the plan:

```bash
./vendor/bin/sail composer update vendor/package --with-all-dependencies
```

After dependency updates, run:

```bash
./vendor/bin/sail composer audit
./vendor/bin/sail npm audit
./vendor/bin/sail artisan test
./vendor/bin/sail composer exec pint -- --test
./vendor/bin/sail npm run build
```

## Project Documentation

The development roadmap, completed tasks, and architectural decisions are documented in [`PROGRESS.md`](PROGRESS.md).

## Project Status

The project is under active development.

The local infrastructure and initial application baseline are configured and verified. Store domain development has not started yet.