## assessment-test
* [General info](#general-info)
* [Technologies](#technologies)
* [Setup](#setup)

## General info
This project demonstrate developing application with php7.4 using docker.
	
## Technologies
Project is created with:
* PHP 7.4
* Docker
* Rabbit MQ
* MYSQL 
* Nginx
	
## Setup
To run this project, install it locally using docker:
 * Go to project directory and run below command :
```
$ docker-compose up -d 
```
* once all containers are up verify rabbit mq at 127.0.0.1:15672 using 
    uname: guest
    password:guest
* Go to php container using below comamand:
```
$ docker exec -it php-box1 bash
```
* Run composer install 
```
$ cd ../assetment_app
$ composer install
```
* Run db migrate to create tables(products and variants).
```
$ php dbmigrate.php
```
* Run testScript for calling api. It will populate data queue.
```
$ php testScript.php 
```
* Run demon.It will read data from queue and insert into database.
```
$ php demon.php 
```
