# Usage

## Clone the repo
git clone https://github.com/riverray121/simpleGoogleAuthMFA.git

## Install composer dependencies
composer install

## Create the SQLite database file
touch mfa.db

## Initialize the database
php init_db.php

## Start the PHP built-in server
php -S localhost:8000 -t public