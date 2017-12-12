# symblog
A simple blag built on Symfony 3 (and PHP 7), MySQL and nginx (optional)

## Requirements

 - PHP 7
   - pdo
   - pdo_mysql
   - intl
   - opcache
 - composer
 - MySQL 5 

## Setup

The simplest setup is via docker-compose, (setting up on a traditional server is also possible):

    cd docker-symfony
    # create an environment file from defaults
    cp .env.dist .env
    # edit .env - set MySQL passwords!
    vim .env
    # build and launch the containers (nginx, php-fpm, mysql)
    docker-compose up -d --build
    # go to the PHP container and install the PHP part
    docker exec -it dockersymfony_php_1 bash bin/firstrun.sh
    
(for the last command, see [docker-symfony/php-fpm/firstrun.sh](docker-symfony/php-fpm/firstrun.sh) )

Note that the default setup creates a single admin user with a hardcoded password - you probably want to remove that from the DefaultContent [fixture](blog/src/AppBundle/DataFixtures/DefaultContent.php), or at least change the password ;)

