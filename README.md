# backend
This project is an exercise to show my way to create a modern application.

## Run in development mode
We use make to simplify interactions with docker.
### Run
In order to be sure that you run the app with optimal infrastructure, we use docker with docker-compose.  
Run the app with the following command to have a complete ready to use environnement:
```sh
make docker-compose-up
```
### Open terminal on php container
```sh
make terminal-php
```
### Composer
#### Install
```sh
make composer-install
```
#### Update
```sh
make composer-update
```
### Database
#### Migration
```sh
make doctrine-migration-migrate
```
### Test
#### Behat
```sh
make test-behat
```
- Coverage
Then you can access to coverage summary on [http://localhost:8081](http://localhost:8081).
#### Static

##### cs
We use Symfony standards (PSR's are including on it), because we assume they're relevants and used by a lot of people.
That's just a code format tool, it's quite interesting when many people works on same project (ex.avoid unnecessary conflicts on PR).
```sh
make static-cs-check
```
To auto fix, use:
```sh
make static-cs-fix
```
##### phpStan
To find bugs and improve code quality by using good practices.
```sh
make static-phpstan
```
##### deptrac
We use deptrac to check Horizontal architecture isn't broken. 
```sh
make static-deptrac
```
### CI-CD
I didn't implement CI because I always did it on gitlab, but never on github.
We have to protect the main branch to not allow commit on it, only merging PR has to be allowed.
So what's important for me on CI is:
#### When commited on non-main branch
Check code quality and run tests.
```sh
make ci-commit-check
```
#### When merging on main branch
Build production app and deploy it (automatically or not depending on deploy choices)
