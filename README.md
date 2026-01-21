# Selah

Selah is an anonymous prayer request system: the public can submit prayers without accounts while administrators review, manage, and track them in a secure backend.

## Tech Stack
- Laravel 12 on PHP 8.4
- Blade, Livewire 3, Alpine
- Tailwind CSS via Vite
- MySQL (or any relational database)

## Core Features
- Anonymous prayer submission
- Admin prayer dashboard
- Mark prayers as prayed
- Audit trail showing who prayed

## Routes
- `/` – Homepage (CTA)
- `/prayers/create` – Public prayer submission
- `/admin/prayers` – Admin prayer dashboard (auth + verified)

## Local Setup
- Clone: `git clone git@github.com:Kuttoh/selah.git && cd selah`
- Install PHP deps: `composer install`
- Install JS deps: `npm install`
- Configure env: `cp .env.example .env`, set database/app values
- Generate key: `php artisan key:generate`
- Run migrations: `php artisan migrate`
- Start dev servers: `composer run dev` (serves Laravel, queues, logs, Vite)

## Notes
- Public submissions require no authentication
- Admin routes require login and verified email
