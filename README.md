# Klydos - AI Powered QA Testing Platform

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

## About Klydos

**Klydos** is an AI-powered QA testing platform that automates website testing with plain English instructions. Describe your test in natural language, and our AI agent executes it instantly—no code required.

### Key Features

- **Natural Language Input**: Simply describe your test scenario in plain English (e.g., "Check if the login button works"), and Klydos translates it into executable test scripts automatically.

- **Instant Simulation**: Run tests in seconds with our optimized backend simulator that checks external and internal routes without browser overhead.

- **Detailed Reporting**: Get granular feedback with step-by-step execution logs, failure analysis, and historical performance trends.

- **Website Management**: Add and manage multiple websites, create test definitions, and track test runs with comprehensive reporting.

## Technology Stack

- **Framework**: Laravel 12
- **PHP**: ^8.2
- **Frontend**: Tailwind CSS, Blade Templates
- **Database**: SQLite (default, configurable)

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd Website_testing_agent
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Set up environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Run migrations and seeders:
```bash
php artisan migrate --force
php artisan db:seed
```

5. Build frontend assets:
```bash
npm run build
```

6. Start the development server:
```bash
php artisan serve
```

Or use the convenient dev script that runs everything:
```bash
composer run dev
```

## Demo Credentials

After running the database seeder, you can use the following demo accounts:

### Regular User
- **Email**: `test@example.com`
- **Password**: `password`
- **Role**: User

### Admin User
- **Email**: `admin@klydos.com`
- **Password**: `password`
- **Role**: Admin

## Usage

1. **Register/Login**: Create a new account or use the demo credentials above.

2. **Add a Website**: Navigate to the websites section and add the URL of the website you want to test.

3. **Create Test Definitions**: Define your test scenarios using natural language descriptions.

4. **Run Tests**: Execute your test definitions and view detailed reports with execution logs and results.

5. **View Reports**: Access comprehensive reports showing test execution history, success rates, and detailed logs.

## Development

### Running Tests
```bash
composer run test
```

### Code Style
This project uses Laravel Pint for code formatting:
```bash
./vendor/bin/pint
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
