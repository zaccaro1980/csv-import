# CSV search

This project is based on the Laravel PHP Framework, and implement the csv search functionality by using the command line (php artisan)

## Setup the project

Clone the repository into an empty folder, a docker compose file is included in the project so you can use it to test locally, or you can use Composer and PHP of your local machine (PHP >= 7.2)

## Setup the project by using docker

Install docker community edition, open a terminal, and then run the following commands:
```
docker-compose up -d
docker-compose exec php-fpm bash
composer install
```

## Setup the project by using local Composer and PHP

Open a terminal, and then run the following commands:
```
composer install
```

## Run the project
Copy the csv file of your choice into the storage/app folder, here below an example of the csv file:
```
1,Mario,Rossi,12/04/1980
2,Giuseppe,Verdi,23/04/1981
3,Paola,Rossi,10/05/1979
```

on the local terminal or the bash console in the docker container, launch the command to start the search procedure:
```
php artisan csv:search test.csv 2 Rossi
```

Happy searching!