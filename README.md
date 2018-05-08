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

- Run once to create a env variable which contains the local user id. This is needed as mapping information for gosu:   `./build-scripts/docker/user.sh`

- Start containers: `docker-compose -f docker-compose-dev.yml up -d`

- Run a bash inside the http-svc container `docker-compose -f docker-compose-dev.yml run --rm http-svc bash`

- Run `composer install` inside the container

- Browse to <http://localhost:8080/> to see the API.

Tests and Static Code Analysis
------------------------------

Code quality is checked using a couple of tools, some of which are slightly
redundant. However, not every tool has all the features, which is why there is
some overlap.

The following describes how these tools are run on the CI system and how they
should be run locally with a dev setup. Most tools are installed to and run from within the
*http-svc* container. Prefix these commands with `docker-compose -f docker-compose-dev.yml run --rm http-svc`
to run it from outside the container.

### PHPstan

Yet another static code analyzer. Varying levels of strictness (1-7) can be
applied.

    vendor/bin/phpstan analyse src tests features --level=7
    

### PHPUnit

Default PHP unit test framework.

    vendor/bin/phpunit


### Behat

For behat we need a test database which you can create with:

 ``mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -e 'CREATE DATABASE IF NOT EXISTS `category_test`' ``
 
behat is run via

    vendor/bin/behat

    
### PhpCS 

when all tests are green, please verify your code style quality by running:
    
    vendor/bin/phpcs
