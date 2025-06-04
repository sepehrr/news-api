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

Please make sure you have docker installed on your machine. You can download it from [here](https://docs.docker.com/get-docker/).

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
