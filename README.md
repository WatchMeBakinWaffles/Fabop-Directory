# FABOP directory

Directory management application of the organization [La Fabrique Opéra Val de Loire](http://www.lafabriqueopera-valdeloire.com/) under Apache 2.0 license.

## Technicals details

The project is maintained under Symfony Frameworks and is using php as back-end main language.

Front-end is maintained with npm modules as gulp-sass, bootstrap and jquery.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

1. Make sure you have php7.2, composer installed.

    **Install PHP 7.2**
    
    `apt-get update`
    
    `apt-get install php-cli`
    
    `apt-get install php-curl php-gd php-intl php-json php-mbstring php-xml php-zip php-mysql php-mongodb`
    
    **Install composer**
    
    `apt install composer`

2. Install git for being able to clone the project, make commits and push, etc...

    `apt-get install git`
    
3. Install nodejs and yarn.

    `apt-get install nodejs npm`
    
    `curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -`
    
    `echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list`
    
    `apt-get update && apt-get install yarn`

### Installing

1. First, you need to clone the project and install dependencies with following command lines :

    `git clone https://gitlab.com/moulagofre/fabop-directory`
    
    `cd fabop-directory`
    
    `yarn install`
    
    `composer install`

2. Then, when cloning is complete, you can run migrations and gulp routines to make front-end and back-end working.

    `yarn run gulp styles`
    
    `yarn run gulp js`
    
    `yarn run gulp fa`
    
    `yarn run gulp animations`
    
    `yarn run gulp images`
    
    `yarn run gulp fonts`

### Running the tests

1. Test mongodb manager (custom data retriever) :

    You can run tests with :
    
    `php ./bin/phpunit tests/MongoManagerTest.php --testdox`
    
    OR
    
    `php ./bin/phpunit tests/MongoManagerTest.php` if you want to check details and errors.
    
    Those tests may not pass if some database's data are not basicaly set.

## Deployment / production server installation

*Will come later*

## Built With

* Symfony - The web framework used (PHP)
* npm/yarn - Dependency Management
* Bootstrap - CSS framework
* jquery - JS framework