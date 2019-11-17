# MVC framework

A custom MVC framework developed in OO PHP.


## Configuration
Open the terminal and run `$ cp .env.example .env` to create a new _.env_ file, or open `.env.example` and save it as `.env`.
Once the _.env_ file is created set the env variables. These variables are 

1. either app/framework specific e.g.
- _APP_NAME_ is used to define the name of the app we are building
- _APP_ENV_ is used to specify in which environment the framework is running (is it local, staging or production?)

2. environment specific e.g.
- _APACHE_SERVER_NAME_ is used to specify a domain name within the apache config
- _PHP_DATE_TIMEZONE_ is used to specify the server's date timezone
- _PHP_DISPLAY_ERRORS_ and _PHP_ERROR_REPORTING_ are used to specify whether to display any php errors and warnings on screen. Ideally these should be switched off in a production/live environment.

Will also need to create a database connection. If using Docker please refer to the [Docker](https://github.com/ltdev22/mvcframework#docker) section on how to setup.


## Docker

If you 're running the framework on Docker

- Run `$ docker-compose up` to launch the containers / the framework app.
- Run `$ docker-compose stop` to stop the containers, or use the key shortcuts `ctrl+c`.
- Run `$ docker-compose exec app bash` to enter the _app_ container.
- Run `$ docker-compose up --build` to build the image used for the _app_ container and then run the containers. Since Composer is intalled within the Dockerfile of the app, this command will also run `$ composer install` in order to install all the required packages.

#### To use Composer and run commands
- You can either run `$ docker-compose exec app bash` and then `$ composer <the composer command here>` within the running _app_ container.
- Or run in the local cli `$ docker run --rm --interactive --tty --volume $PWD:/app composer <the composer command here>` using the Composer official image

## Setup a database connection

1. When using [Docker](https://github.com/ltdev22/mvcframework#docker) and the containers are up and running (docker-compose up) on a new tab and run the command `$ docker-compose exec mysql bash`.
2. Once you enter the _mysql_ container run the command `$ mysql -u root -p` to login to MySQL. Type the password for the _root_ use to login.
3. Then we need to create a new database and a new user
```mysql
-- Create a new database
$ CREATE DATABASE `database_name_here` CHARACTER SET utf8 COLLATE utf8_unicode_ci;

-- Create the user and grant privileges too
$ GRANT ALL PRIVILEGES ON `database_name_here`.* TO 'user_name_here'@'%' IDENTIFIED BY 'password_here';

-- To confirm privileges are set
$ SHOW GRANTS FOR 'user_name_here'@'%';
```
4. On a new tab on the terminal run the command `$ docker network ls` to list all the available networks.
5. To get the ip address of the _mysql_ container run the command `$ docker network inspect <name of the network here>`.
6. In the json outputed look for the ip address within the running containers for mysql.
7. Open the `.env` file and set 
```
DB_HOST=     // the ip address you 've got from steps 5 & 6
DB_DATABASE= // the name of the database you've got from step 3
DB_USERNAME= // the name of the user you've got from step 3
DB_PASSWORD= // the name of the password you've got from step 3 for the user

NOTE: in the .env file there are some additional variables set in the Environment configuration. 
These (by default) should get the same settings as the DB_* ones.
```
8. (Optional) In your mysql gui tool (e.g. MySQL Workbench) create a new connection using the same settings as on step 7


## Cmder

The _Cmder_ is the cli tool included within the framework and it is driven by the powerful Symfony Console component. It provides a number of helpful commands to use while developing your application and help to speed up your work. It's something similar to Laravel's artisan cli tool. Here is a list with some of the commands provided
by Cmder

- To list all the available commands: `$ php cmder`
- To generate a new controller: `$ php cmder make:controller FooController` or `$ php cmder make:controller Some\\Nested\\FooController`
- To generate a new service provider: `$ php cmder make:provider FooProvider`. __Note:__ running this command will only generate the new provider class. In order the provider to work within the framework you 'll need to register it within the `providers` list located in the `config/app.php`
- To generate a new middleware: `$ php cmder make:middleware FooMiddleware`  or `$ php cmder make:middleware Some\\Nested\\FooMiddleware`. __Note:__ running this command will only generate the new middleware class. In order the middleware to work within the framework you 'll need to register it within the `middleware` list located in the `config/app.php`
- To generate a new custom command: `$ php cmder make:console FooCommand`
- To generate a new exception: `$ php cmder make:exception FooException`. __Note:__ running this command will only generate the new exception. You can use `App\Exceptions\ExceptionHandler` to handle your custom exception with a custom method (see other exceptions for ref) otherwise will default to `unhandledException`.
- To run migration and seed commands please refer to the [Migrations](https://github.com/ltdev22/mvcframework#migrations) section for more details


## Migrations

Migrations is a nice way of versioning the database schema. This framework is using the _Phinx_ migrations, so for more details please refer to the official [documentation](http://docs.phinx.org/en/latest/). Although the recommended way to run migrations and seeds is through the Cmder, note that you can also do it using the `vendor/bin/phinx` command.

#### Run migrations using Cmder (recommended)

Running migrations and seeds using the Cmder is very easy. Remember you can always use the _-h_ option to see more details for each command. Here's a list of some usefull commands:

- To create a new migration run: `$ php cmder db:create CreateFooMigration`
- To run all available migrations run: `$ php cmder db:migrate`
- To run a specific migration run: `$ php cmder db:migrate -t <specify the timestamp>`
- To rollback migrations run: `$ php cmder db:rolback`
- To print the current status of the migrations run: `$ php cmder db:status`
- To create a new seed run `$ php cmder db:seed:create CreateFooSeed`
- To run all available seeds run: `$ php cmder db:seed:run`
- To run a specific seed run: `$ php cmder db:seed:run -s <specify the name>`

#### Run migrations using Phinx

Since the config settings for the Phinx migrations are living within the _config_ directory, it is __important__ to include the config on every command using `-c ./config/phinx.php`. So for example:

- To create a new table run: `$ vendor/bin/phinx create CreateFooMigration -c ./config/phinx.php`
- To run all the available migrations: `$ vendor/bin/phinx migrate -c ./config/phinx.php`
etc 
