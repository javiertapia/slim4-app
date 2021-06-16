# Slim 4 App

Based in [Slim 4 Skeleton Application](https://github.com/slimphp/Slim-Skeleton), this project enhances the codebase using a more complete Docker stack, .env file support and database-connection setup.

## Features

* Based in Slim 4 Framework with Slim PSR-7 implementation and PHP-DI container implementation.
* Uses the Monolog logger.
* Built for Composer (Set up a new Slim Framework quick and easy).
* Support for `.env` config file.
* Use `illuminate/database` Query Builder.
* Useful `Makefile` shortcuts.

## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

```bash
git clone https://github.com/javiertapia/slim4-app.git [my-app-name]
```

Where `[my-app-name]` is the directory name for the new application.

* The host document root is the `public/` directory.
* The `logs/` directory must be web writable.

To run the application in development, using `docker-compose`: 

```bash
cd [my-app-name]
docker-compose up
```

The first time this command _pull_ the required docker images and build the services. This can take a while.

After that, open `http://localhost:8088` in your browser.

Create `users` table using:

```sql
create table users
(
    id int auto_increment
        primary key,
    username varchar(255) null,
    email varchar(255) null,
    first_name varchar(255) null,
    last_name varchar(255) null,
    constraint username
        unique (username)
)
    collate=utf8mb4_unicode_ci;
```

## Makefile shortcuts

At the command line, put into the project directory and run this shortcuts:

`make d-up`

Start all the docker containers in _detached_ mode (i.e, these will run in background).
Is a shortcut for the command `docker-compose up -d`.

`make d-down`

Stop all docker containers. Is a shortcut of `docker-compose down`.

`make d-bash`

Open the shell `/bin/sh` into the `php` container's service. Is a shortcut of `docker-compose exec php /bin/sh`.

`make d-test`

Run `phpunit` tests at `/www/tests` directory, using `/www/tests/bootstrap.php` as bootstrap file.

`make d-composer`

Run `composer install` from the container's image, over the project's `composer.json` file.
Is a shortcut of `docker-compose exec php /bin/sh -c "cd /www && composer install"`

`make phpstan [DIR=<dir>]`

Execute PhpStan from his official docker image, over a specified directory. The `DIR` parameter is optional.
Is a shortcut of 
`docker run --rm -v .:/www ghcr.io/phpstan/phpstan --level=7 analyse /www/src/<dir> --autoload-file /www/vendor/autoload.php`

`make help`

Show a summary of the available `make` commands.

## (Desired) features

- [x] Configuration object
- [x] Custom error handler
- [x] Error logger
- [x] .env support
- [x] Setup database connection
- [x] Setup mailer 
- [x] Twig Template system
- [x] MtHaml Template system
