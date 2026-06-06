# SBP

SBP is a Laravel-based subscription plan project with role-based access control.

It includes:

- user registration and login
- admin and user roles
- plan crud for admins
- subscription purchase, upgrade, downgrade, renew, and cancel flows for users
- Razorpay payment checkout with payment verification
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
  - pay through Razorpay checkout

## Project Structure

- `app/Http/Controllers` - request handling
- `app/Services` - business logic
- `app/Repositories` - database access layer
- `app/Models` - Eloquent models and scopes
- `app/Http/Requests` - validation rules
- `resources/views` - Blade templates
- `database/migrations` - database schema
- `database/seeders` - seed data

## Payment Flow

The app uses a payment-first flow.

1. User selects a plan from the plans page.
2. The app creates a pending payment record.
3. The app creates a Razorpay order.
4. Razorpay checkout opens in the browser.
5. Razorpay returns `razorpay_payment_id`, `razorpay_order_id`, and `razorpay_signature`.
6. The backend verifies the signature.
7. If verification passes, the payment is marked as `success`.
8. The subscription is then created or updated.

Current payment routes:

- `GET /payment/page/{plan}` - show Razorpay checkout page
- `POST /payment/make-payment/{plan}` - verify payment and activate subscription

## Environment

Add these keys in `.env` for Razorpay:

```env
RAZORPAY_KEY_ID=your_key_id
RAZORPAY_KEY_SECRET=your_key_secret
```

The same keys are already mapped in `config/services.php`.

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
- The app keeps one subscription row per user and updates it on payment success.
