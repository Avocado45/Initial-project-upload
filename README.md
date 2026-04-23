Running The Project

Environment
This project was developed and tested in Ubuntu / WSL using Laravel Sail.

REQUIREMENTS

Install docker
Install WSL / Ubuntu / Linux shell environment

Run the following commands from the project root:

cp .env.example .env

composer install

./vendor/bin/sail up -d

./vendor/bin/sail artisan key:generate

./vendor/bin/sail artisan migrate

./vendor/bin/sail npm install

./vendor/bin/sail npm run build

Web Application should be online at http://localhost
