# MVC framework

A custom MVC framework developed in OO PHP.


## Docker

If you 're running the framework on Docker

- Run `$ docker-compose up` to launch the containers / the framework app.
- Run `$ docker-compose stop` to stop the containers, or use the key shortcuts `ctrl+c`.
- Run `$ docker-compose exec app bash` to enter the _app_ container.
- Run `$ docker-compose up --build` to build the image used for the _app_ container and then run the containers. Since Composer is intalled within the Dockerfile of the app, this command will also run `$ composer install` in order to install all the required packages.

To use Composer and run commands
- You can either run `$ docker-compose exec app bash` and then `$ composer install` within the running _app_ container.
- Or run in the local cli `$ docker run --rm --interactive --tty --volume $PWD:/app composer <the composer command here>` using the Composer official image
