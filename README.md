[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](https://opensource.org/licenses/Apache-2.0)
[![AppVeyor](https://img.shields.io/appveyor/ci/moulagofre/fabop-directory.svg)]()
[![pipeline status](https://gitlab.com/moulagofre/fabop-directory/badges/master/pipeline.svg)](https://gitlab.com/moulagofre/fabop-directory/commits/master)
[![coverage report](https://gitlab.com/moulagofre/fabop-directory/badges/master/coverage.svg)](https://gitlab.com/moulagofre/fabop-directory/commits/master)

# FABOP directory

Directory management application of the organization [La Fabrique Opéra Val de Loire](http://www.lafabriqueopera-valdeloire.com/) under Apache 2.0 license.

## Technicals details

The project is maintained under Symfony Frameworks and is using Php as back-end main language.

Front-end is maintained with npm modules as gulp-sass, bootstrap and jquery. Html templates are using Twig.

## Docker stack

The project handle a Docker configuration made for a 4 containers stack :

    - web : Nginx container
        port : 80
        image : debian:stretch

        Handle Http requests through Nginx web reverse proxy

    - php : Php-fpm container
        port : 9000
        image : php:7.4-fpm

        Handle php backend scripts execution. Serve Nginx on port 9000.

    - mysqldb : MariaDB MySQL Database
        port : 3306
        image : mariadb

        Handle relational type data through Doctrine component.

    - mongodb : MongoDB NoSQL Database
        port : 27017
        image : mongo:latest

        Handle collection/document type data through custom Data management system.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites
    
1. Install nodejs and yarn.

    `apt-get install nodejs npm`
    
    `curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -`
    
    `echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list`
    
    `apt-get update && apt-get install yarn`

2. Docker dependencies

    Ubuntu : https://docs.docker.com/install/linux/docker-ce/ubuntu/

    Debian : https://docs.docker.com/install/linux/docker-ce/debian/

    Fedora : https://docs.docker.com/install/linux/docker-ce/fedora/
    
    CentOS : https://docs.docker.com/install/linux/docker-ce/centos/

    Windows : https://docs.docker.com/docker-for-windows/install/
    
3. Install git for being able to clone the project, make commits and push, etc...

    `apt-get install git`

### Setting up Docker dev environment

1. First, you need to clone the project and install yarn dependencies

    `git clone https://tuleap.fiction-factory.fr/plugins/git/fabop/fabop-directory.git`
    
    `cd fabop-directory/app`
    
    `yarn install`

2. Then, When cloning is complete :

    `yarn run gulp styles`
    
    `yarn run gulp js`
    
    `yarn run gulp fa`
    
    `yarn run gulp animations`
    
    `yarn run gulp images`
    
    `yarn run gulp fonts`

3. Edit app/.env and set `APP_ENV` to `dev`

4. Set DB passwords in `docker-compose.yml` and copy them into `app/.env` by replacing `<SET PASSWORD HERE>` placeholders. Set also mongodb password in `build/mongodb/mongo-init.js`.

5. Now, build docker images and start it :

    `docker-compose build` (may take few minutes)
    
    `docker-compose up`

    Use `docker-compose start/stop` to manage containers (or any GUI Docker containers manager)

6. Install composer dependencies by your php container : 
   
   `docker exec <php container> composer install `
   `docker exec <php container> chown -R :www-data /var/www`
            
7. Make db migrations by using following commands :

    `docker-compose exec php bin/console make:migration`

    `docker-compose exec php bin/console doctrine:migrations:migrate`

8. If you need to enter containers terminal :

    `docker exec -it <mycontainer> bash`
        
9. Build the app svelte:
     `yarn encore dev`
     
10. Get your user root (mail : root@root.fr | mdp : root) :

    `docker-compose exec php bin/console doctrine:fixtures:load`

11. To get your ip container :
    `docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' <Container Name >`
       
12. Your app is active and you can see your project at localhost:80 or your web container ip.


### Setting up the development environment without Docker

1.1. Make sure you have php7.4, composer and mongodb installed :

   **Install PHP 7.4**
        
   `apt-get update`
        
   `apt-get install php-cli`
        
   `apt-get install php-curl php-gd php-intl php-json php-mbstring php-xml php-zip php-mysql php-mongodb`    
    
   **Install composer**
    
   `apt install composer`

1.2. First, you need to clone the project and install dependencies with following command lines :

    `git clone https://tuleap.fiction-factory.fr/plugins/git/fabop/fabop-directory.git`
    
    `cd fabop-directory/app`

    `git checkout develop`
    
    `yarn install`
    
    `composer install`

2. Then, When cloning is complete, replace in app/assets/js/scripts.js every `localhost` by `localhost:8000`

    and run gulp routines to make front-end and back-end working.

    `yarn run gulp styles`
    
    `yarn run gulp js`
    
    `yarn run gulp fa`
    
    `yarn run gulp animations`
    
    `yarn run gulp images`
    
    `yarn run gulp fonts`

4. Edit app/.env and set `APP_ENV` to `dev`

5. Replace 
    - `DATABASE_URL="mysql://app_access:<SET PASSWORD HERE>@mysqldb:3306/fabop_directory"` 
                                                by
      `DATABASE_URL="mysql://[VOTRE LOGIN]:[VOTRE LOGIN]@servinfo-mariadb:3306/sf[VOTRE LOGIN]"`

    Note : Remplace [VOTRE LOGIN] by your session login

    - `MONGODB_URL=mongodb://app_access:<SET PASSWORD HERE>@mongodb:27017/fabop_directory`
                                                by
      `MONGODB_URL="mongodb://localhost:27017"`

    Warning : Do not forget the quotation marks

6. Now, you can run migrations

    `php bin/console make:migration`

    If you have a warning, don't care about it, continue.

    `php bin/console doctrine:migrations:migrate`

    Note : Answer `y` to the question :

        WARNING! You are about to execute a database migration that could result in schema changes and data loss. Are you sure you wish to continue? (y/n)

7. Build the app svelte:
    `yarn encore dev`
    

8. Finally, you can start the project

    `php bin/console server:start`

### Running the tests

1. Test mongodb manager (custom data retriever) :

    You can run tests with :
    
    `php ./bin/phpunit tests/MongoManagerTest.php --testdox`
    
    OR
    
    `php ./bin/phpunit tests/MongoManagerTest.php` if you want to check details and errors.
    
    **Those tests may not pass if some database's data are not precisely set.**

### Loading fixtures
    
`docker-compose exec php bin/console doctrine:fixtures:load`
    

## Deployment / production server installation

*Will come later*

## Built With

* Php 7.2.16
* JavaScript
* Twig

* Symfony 4.1.7 - The web framework used (PHP)
* npm/yarn - Dependency Management
* Bootstrap 4 - CSS framework
* jquery - JS framework
* fontawesome - Icons font
* Docker - Containers

* MariaDB - MySQL Database
* MongoDB - NoSQL Database
