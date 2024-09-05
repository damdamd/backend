# backend
This project is an exercise to show my way to create a modern application.

## Run in development mode
### Run
In order to be sure that you run the app with optimal infrastructure, we use docker.  
Run the app with the following command :
```sh
env UID=${UID} docker-compose -f docker-compose.yml -f docker-compose.override.yml up -d
```
### Composer
#### Install
```sh
docker-compose exec php composer install
```
#### Update
```sh
docker-compose exec php composer update
```
### Database
#### Migration
```sh
docker-compose exec php bin/console doctrine:migrations:migrate
```
### Test
#### Launch tests
```sh
docker-compose exec php vendor/bin/behat
```
#### Coverage
Then you can access to coverage summary on [http://localhost:8081](http://localhost:8081).
