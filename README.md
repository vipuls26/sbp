# SBP

SBP is a Laravel subscription billing app with role-based access control, Stripe Checkout, and local payment tracking.

It supports:

- user registration and login
- admin and user roles
- plan CRUD for admins
- subscription purchase, upgrade, downgrade, renew, and cancel for users
- Stripe Checkout through Laravel Cashier
- invoice download for paid subscriptions
- soft deletes for users and plans
- Blade views styled with Tailwind CSS

## Tech Stack

- Backend: Laravel 13
- PHP: 8.3+
- Composer: 2.x
- Database: MySQL
- Frontend: Blade
- Styling: Tailwind CSS
- Payments: Laravel Cashier + Stripe

## Composer Packages and Versions

Main packages used in this project:

- `laravel/framework` `^13.8`
- `laravel/cashier` `^16.5`
- `stripe/stripe-php` `^17.3`
- `laravel/tinker` `^3.0`

Dev packages:

- `fakerphp/faker` `^1.23`
- `laravel/pail` `^1.2.5`
- `laravel/pao` `^1.0.6`
- `laravel/pint` `^1.27`
- `mockery/mockery` `^1.6`
- `nunomaduro/collision` `^8.6`
- `phpunit/phpunit` `^12.5.12`

## Main Roles

### Admin

- manage plans
- view all users
- view subscribers
- block and unblock users

### User

- view active plans
- subscribe to a plan
- upgrade or downgrade a subscription
- cancel a subscription
- download invoice PDFs

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

The app uses Stripe Checkout through Laravel Cashier.

1. User selects a plan from the plans page.
2. The app creates a Stripe Checkout session for the plan's Stripe price id.
3. Stripe shows the payment page in the browser.
4. Stripe redirects back to the app after payment.
5. The app stores the local payment record.
6. The subscription row is updated in the database.
7. Stripe webhooks are handled by Cashier at `/stripe/webhook`.

### Subscription Routes

- `POST /subscription/{plan}/store-subscription` - start Stripe checkout
- `GET /subscription/{plan}/success` - handle successful checkout redirect
- `GET /subscription/{plan}/cancel` - handle canceled checkout redirect
- `PUT /subscription/{plan}/update-subscription` - update subscription plan
- `POST /subscription/cancel-subscription` - cancel subscription at period end
- `GET /subscription/payment/{payment}/download-invoice` - download invoice PDF
- `POST /stripe/webhook` - Cashier Stripe webhook endpoint

## Database

The app uses these main tables:

- `users`
- `roles`
- `plans`
- `subscriptions`
- `subscription_items`
- `payments`

`subscription_items` is created by Laravel Cashier for subscription line items.

## Stripe Setup

Add these keys to `.env`:

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

These values are mapped in `config/services.php`.

The plan seeder creates or syncs Stripe prices for the demo plans using the Stripe products you already created in the Stripe Dashboard. You do not need to create the prices manually.

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

6. If you want to sync the Stripe plan catalog again later, run:

```bash
php artisan stripe:sync-plans
```

7. Build frontend assets:

```bash
npm run build
```

8. Start the app:

```bash
php artisan serve
```

## Composer Scripts

The project includes these useful Composer scripts:

- `composer setup` - install dependencies, prepare `.env`, migrate, and build assets
- `composer dev` - run the Laravel server, queue listener, logs, and Vite together
- `composer test` - clear config cache and run tests

## Seeded Demo Users

The default seeder creates these demo users:

- `testuser1` / `user1@test.com`
- `testuser2` / `user2@test.com`
- `testuser3` / `user3@test.com`
- `Admin` / `admin@gmail.com`
- `testuser4` / `user4@test.com`
- `testuser5` / `user5@test.com`

## Notes

- The app uses role-based middleware for admin and user routes.
- Plan and subscription rules are handled in the service and repository layers.
- Soft deletes are used for users and plans.
- Cashier manages Stripe subscription state, and the app also stores a local payment record for reporting.
- Users can download the invoice PDF from the plans page after a payment has a Stripe invoice id.
- The codebase is written to stay beginner friendly and readable.


## Port forward
- Run this command of stripe in install in your system otherwise configure with stripe or ngrok
- stripe listen --forward-to localhost:8000/api/webhooks/stripe
