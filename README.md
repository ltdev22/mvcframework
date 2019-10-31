# MVC framework

A custom MVC framework developed in OO PHP.


## Configuration
Open the terminal and run `$ cp .env.example .env` to create a new _.env_ file, or open `.env.example` and save it as `.env`.
Once the _.env_ file is created set the env variables. These variables are 

1. either app/framework specific e.g.
- _APP_NAME_ is used to define the name of the app we are building
- _APP_ENV_ is used to specify in which environment the framework is running (is it local, staging or production?)

2. or environment specific e.g.
- _APACHE_SERVER_NAME_ is used to specify a domain name within the apache config
- _PHP_DATE_TIMEZONE_ is used to specify the server's date timezone
- _PHP_DISPLAY_ERRORS_ and _PHP_ERROR_REPORTING_ are used to specify whether to display any php errors and warnings on screen. Ideally these should be switched off in a production/live environment.


## Docker

If you 're running the framework on Docker

- Run `$ docker-compose up` to launch the containers / the framework app.
- Run `$ docker-compose stop` to stop the containers, or use the key shortcuts `ctrl+c`.
- Run `$ docker-compose exec app bash` to enter the _app_ container.
- Run `$ docker-compose up --build` to build the image used for the _app_ container and then run the containers. Since Composer is intalled within the Dockerfile of the app, this command will also run `$ composer install` in order to install all the required packages.

To use Composer and run commands
- You can either run `$ docker-compose exec app bash` and then `$ composer install` within the running _app_ container.
- Or run in the local cli `$ docker run --rm --interactive --tty --volume $PWD:/app composer <the composer command here>` using the Composer official image
