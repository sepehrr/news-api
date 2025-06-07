# News Aggregator API

## About Project

In this project, we'll have a simple web application that lists articles and we gather articles rom different sources. Furthermore, we'll have a user authentication system that allows users to create accounts and log in to the application and set their preferences for the articles they want to see. The application will also have a search functionality that allows users to search for articles based on keywords.
We'll use the Laravel framework to build the application.

## How to Run

First run dependency installation commands:

```bash
composer install
```

Then run the following commands:

```bash
cp .env.example .env
php artisan key:generate
```

Please make sure you have docker installed on your machine. You can download it from [Docker Website](https://docs.docker.com/get-docker/).

We used [Laravel Sail](https://laravel.com/docs/12.x/sail) to prepare Docker for us. To run the application, you can use:

```bash
./vendor/bin/sail up
```

However, instead of repeatedly typing vendor/bin/sail to execute Sail commands, you may wish to configure a shell alias that allows you to execute Sail's commands more easily:

```bash
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```

Now you can run the application by typing:

```bash
sail up
```

Run migrations and seeders:

```bash
sail artisan migrate --seed
```

This will start the application in the background. You can access the application by visiting `http://localhost:80` in your browser.

If you prefer, there are other ways to run the application, for example, you can use [Laravel Herd](https://laravel.com/docs/12.x/installation#installation-using-herd) to run the application.

## Environment Variables

After copying the `.env.example` file to `.env`, you'll need to configure the following environment variables:

```bash
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

# Mail Configuration (if needed)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# News API Configuration
NEWS_API_KEY=your_api_key_here
```

Make sure to replace the placeholder values with your actual configuration values.

## Running Tests

The project uses PHPUnit for testing. You can run the tests using the following commands:

```bash
# Run all tests
sail artisan test

# Run specific test file
sail artisan test --filter=TestName

# Run tests with coverage report (requires Xdebug)
sail artisan test --coverage
```

For running tests in the CI environment or when you need to run tests without Sail:

```bash
# Using PHPUnit directly
./vendor/bin/phpunit

# Run specific test suite
./vendor/bin/phpunit --testsuite=Feature
```

The test files are located in the `tests` directory, organized into `Feature` and `Unit` test suites.

## API Documentation

The project uses Swagger/OpenAPI for API documentation. You can access the API documentation in two ways:

1. Visit the root URL of the application (`http://localhost:80`), which will automatically redirect you to the Swagger documentation.

2. Directly access the Swagger UI at `http://localhost:80/api/documentation`

The documentation includes:

- Detailed API endpoints
- Request/response schemas
- Authentication requirements
- Example requests and responses
- Interactive testing interface

To generate or update the API documentation, run:

```bash
sail artisan l5-swagger:generate
```

Note: The documentation is automatically generated based on the OpenAPI annotations in the controllers and resources.
