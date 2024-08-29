# backend
This project is an exercise to show my way to create a modern application.

## Run in development mode
### Run
In order to be sure that you run the app with optimal infrastructure, we use docker.  
Run the app with the following command :
```sh
docker-compose -f docker-compose.yml -f docker-compose-dev.yml up -d
```
### Test
#### Launch tests
```sh
docker-compose exec php vendor/bin/behat
```
#### Coverage
Then you can access to coverage summary on [http://localhost:8081](http://localhost:8081).
