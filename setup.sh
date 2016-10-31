parameters="app/config/parameters.yml"
green="\033[0;32m"
default="\033[0m"

echo -e "Hello, ${green}"$USER"${default}.  This script will set up the Symfony install and add sample data.\n"

chmod 777 var/cache
chmod 777 var/logs
chmod 777 var/sessions

echo -e "Installing assets. ${green}This will take a while.${default}\n"

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

php composer-setup.php

php -r "unlink('composer-setup.php');"

php composer.phar install

cp app/config/parameters.yml.dist $parameters

echo -e "\r"

read -p "Type your database name, followed by [ENTER]: " db_name

read -p "Type your database user, followed by [ENTER]: " db_user

read -p "Type your database password, followed by [ENTER]: " db_password

echo -e "\r"

replace "<database-name>" "$db_name" -- $parameters

replace "<database-user>" "$db_user" -- $parameters

replace "<database-password>" "$db_password" -- $parameters

echo -e "\r"

chmod +x bin/console

bin/console doctrine:schema:update --force

bin/console data:add

echo -e "\r"

echo -e "${green}Install completed.${default}"