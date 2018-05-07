Category Api
=============================

[![Build Status](https://travis-ci.org/Engerim/category-api.svg?branch=master)](https://travis-ci.org/Engerim/category-api)

Setup
-----

- Install Docker or Docker for Mac depending on your operating system.

- Start containers: `docker-compose up -d`

- Browse to <http://localhost:8080/> to see the API.


Setup Dev
---------

- Install Docker or Docker for Mac depending on your operating system.

- Copy the `.env.dist` file to `.env`

- Build a local docker image by executing `make build_app`.

- Start containers: `docker-compose -f docker-composer-dev.yml up -d`

- Run a bash inside the http-svc container `docker-compose -f docker-composer-dev.yml run --rm http-svc bash`

- Run `composer install` inside the container

- Browse to <http://localhost:8080/> to see the API.