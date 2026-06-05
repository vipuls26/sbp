# SBP

SBP is a Laravel-based subscription plan project with role-based access control.

It includes:

- user registration and login
- admin and user roles
- plan crud for admins
- subscription purchase, upgrade, downgrade, and cancel flows for users
- soft delete support for users and plans
- Blade views styled with Tailwind CSS

## Tech Stack

- Backend: Laravel 13
- Frontend: Blade
- Database: MySQL
- Styling: Tailwind CSS

## Main Roles

- `admin`
  - manage plans
  - view subscribers
  - block and unblock users
- `user`
  - view active plans
  - subscribe to a plan
  - update or cancel subscription

## Project Structure

- `app/Http/Controllers` - request handling
- `app/Services` - business logic
- `app/Repositories` - database access layer
- `app/Models` - Eloquent models and scopes
- `app/Http/Requests` - validation rules
- `resources/views` - Blade templates
- `database/migrations` - database schema
- `database/seeders` - seed data

## Setup

1. Install dependencies:

```bash
composer install
npm install
```

2. Copy the environment file:

```bash
cp .env.example .env
```

3. Generate the app key:

```bash
php artisan key:generate
```

4. Configure your database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sbp
DB_USERNAME=root
DB_PASSWORD=
```

5. Run migrations and seeders:

```bash
php artisan migrate --seed
```

6. Build frontend assets:

```bash
npm run build
```

7. Start the app:

```bash
php artisan serve
```

## Useful Commands

- `composer setup` - install dependencies, prepare `.env`, migrate, and build assets
- `composer dev` - run the Laravel server, queue listener, logs, and Vite together
- `composer test` - clear config cache and run tests

## Notes

- This app uses role-based middleware for admin and user routes.
- Plan and subscription rules are handled in the service and repository layers.
- Soft deletes are used for users and plans.

