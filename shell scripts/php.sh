sudo apt-get update
sudo apt-get install php
sudo apt update && sudo apt install wget php-cli php-zip unzip curl
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo apt-get update
sudo apt-get install php7.2-mbstring
mkdir comptest
cd comptest
composer require php-amqplib/php-amqplib
composer update /home/github/comptest
