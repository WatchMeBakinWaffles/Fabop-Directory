# FABOP directory

Application de gestion d'annuaire de l'association [La Fabrique Opéra Val de Loire](http://www.lafabriqueopera-valdeloire.com/) sous licence Apache 2.0.

## Technicals details

The project is maintained under Django Frameworks and using Python as back-end main language.

Front-end is maintained with npm modules as gulp-sass, bootstrap and jquery.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

1. Make sure you have php7.2, Symfony 4.1 installed.

    **Install PHP 7.2**
    
    `apt-get install software-properties-common`
    
    `add-apt-repository ppa:ondrej/php`
    
    `apt-get update`
    
    `apt-get install php7.2`
    
    **Install Symfony 4.1**

2. Install git for being able to clone the project, make commits and push, etc...

    `apt-get install git`
    
3. Install nodejs and yarn.

    `apt-get install nodejs npm`
    
    `curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list`
    
    `apt-get update | apt-get install yarn`

### Installing

1. First, you need to clone the project and install dependencies with following command lines :

    `git clone https://gitlab.com/moulagofre/fabop-directory`
    
    `cd fabop-directory`
    
    `yarn install`

2. Then, when cloning is complete, you can run migrations and gulp routines to make front-end and back-end working.

### Running the tests

*Will come later*

## Deployment / production server installation

*Will come later*

## Built With

* Symfony - The web framework used
* npm/yarn - Dependency Management
* Bootstrap - CSS framework
* jquery - JS framework