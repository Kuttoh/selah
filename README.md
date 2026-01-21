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
  <img width="1440" height="900" alt="Screenshot 2026-01-21 at 16 23 01" src="https://github.com/user-attachments/assets/1a610fe1-7906-485e-b47b-1c6d960f3c69" />

- `/prayers/create` – Public prayer submission
  <img width="1440" height="900" alt="Screenshot 2026-01-21 at 16 23 10" src="https://github.com/user-attachments/assets/b516a303-33dd-4721-8d5e-0d2a40a4ddb8" />

- `/admin/prayers` – Admin prayer dashboard (auth + verified)
  <img width="1440" height="900" alt="Screenshot 2026-01-21 at 16 24 45" src="https://github.com/user-attachments/assets/a06a6f44-3133-4d3f-8ca3-c4f38e612c2e" />
  
  <img width="1440" height="900" alt="Screenshot 2026-01-21 at 16 23 50" src="https://github.com/user-attachments/assets/32be4b0c-df2b-4b73-b987-16e3166d02ec" />

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
