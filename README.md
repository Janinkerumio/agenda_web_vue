# Agenda Web Vue

A modern web application for managing agendas and schedules, built with Laravel, Vue 3, and Inertia.js.

## Features

- Interactive calendar interface
- User authentication and profiles
- Membership management
- Responsive design
- Real-time updates (via Laravel Echo and Pusher, if configured)

## Technology Stack

- **Backend:** Laravel 10
- **Frontend:** Vue 3, Inertia.js
- **Styling:** Tailwind CSS
- **Build Tool:** Vite
- **Database:** SQLite (default), configurable to MySQL/MariaDB
- **Calendar:** FullCalendar.io
- **Icons:** Feather Icons

## Installation

1. Clone the repository
2. Copy `.env.example` to `.env` and configure your environment
3. Install PHP dependencies:
   ```bash
   composer install
   ```
4. Install JavaScript dependencies:
   ```bash
   npm install
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Run database migrations:
   ```bash
   php artisan migrate
   ```
7. (Optional) Seed the database:
   ```bash
   php artisan db:seed
   ```
8. Start the development servers:
   ```bash
   # In one terminal
   php artisan serve
   # In another terminal
   npm run dev
   ```

## Usage

Access the application at `http://localhost:8000` (or whatever URL you configured in `.env`).

## Configuration

- Environment variables are stored in `.env`
- Important variables:
  - `APP_NAME`: Name of your application
  - `APP_URL`: Base URL for the application
  - `DB_CONNECTION`: Database connection (sqlite, mysql, etc.)
  - `VITE_APP_NAME`: Vue frontend app name (mirrors APP_NAME)

## Building for Production

```bash
npm run build
```

This will optimize and minify your assets for production.

## License

This project is open-source and available under the [MIT License](LICENSE).
