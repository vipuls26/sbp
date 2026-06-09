# SBP

SBP is a Laravel-based subscription plan project with role-based access control.

It includes:

- user registration and login
- admin and user roles
- plan crud for admins
- subscription purchase, upgrade, downgrade, renew, and cancel flows for users
- Stripe Checkout with Laravel Cashier
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
  - pay through Stripe Checkout

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

The app now uses Stripe Checkout through Laravel Cashier.

1. User selects a plan from the plans page.
2. The app starts a Stripe Checkout session for the selected Stripe price id.
3. Stripe opens the checkout page in the browser.
4. Stripe redirects back to the app after payment.
5. The app stores the payment record and updates the subscription.
6. Stripe webhooks are handled by Cashier at `/stripe/webhook`.

Current subscription routes:

- `POST /subscription/{plan}/store-subscription` - start Stripe checkout
- `GET /subscription/{plan}/success` - handle the success redirect
- `GET /subscription/{plan}/cancel` - handle the cancel redirect
- `POST /stripe/webhook` - Cashier Stripe webhook endpoint

## Environment

Add these keys in `.env` for Stripe Cashier:

```env
STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret
STRIPE_WEBHOOK_SECRET=your_stripe_webhook_secret
STRIPE_CURRENCY=inr
STRIPE_PRODUCT_HOBBY_MONTHLY=prod_xxx
STRIPE_PRODUCT_BASIC_MONTHLY=prod_xxx
STRIPE_PRODUCT_PRO_MONTHLY=prod_xxx
STRIPE_PRODUCT_HOBBY_ANNUAL=prod_xxx
STRIPE_PRODUCT_BASIC_ANNUAL=prod_xxx
STRIPE_PRODUCT_PRO_ANNUAL=prod_xxx
```

The same keys are mapped in `config/services.php`.

The app creates the Stripe prices for the demo plans through the Stripe API and attaches them to the Stripe products you already created in the dashboard. You do not need to create the prices manually.

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

If you only want to sync the Stripe catalog again later, run:

```bash
php artisan stripe:sync-plans
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
- Cashier manages Stripe subscription state, and the app also stores a local payment record for reporting.
- The plan seeder now creates real Stripe products and price ids before storing the plans locally.
