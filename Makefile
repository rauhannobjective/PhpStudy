
#!make
include .env
export $(shell sed 's/=.*//' .env)

t=

up:
	docker-compose up
upd:
	docker-compose up -d
down:
	docker-compose down
downs:
	docker-compose down -v --remove-orphans
build:
	docker-compose up --build
buildd:
	docker-compose up --build -d
sh:
	docker exec -it -u nginx phpstudy-php /bin/bash
server:
	docker exec -it phpstudy-nginx /bin/bash
db:
	docker exec -it phpstudy-db bash -c "mysql -u ${DB_USER} -p'${DB_PASS}' ${DB_NAME}"
install:
	composer install
dum:
	composer dump-autoload
cache:
	php artisan cache:clear && php artisan config:cache && php artisan route:clear && php artisan route:cache
reset:
	docker-compose down -v --remove-orphans && docker system prune -a -f && docker-compose up --build
units:
	vendor/bin/phpunit --testsuite u
functionals:
	vendor/bin/phpunit --testsuite f
test:
	vendor/bin/phpunit
psalm:
	vendor/bin/psalm
seed:
	vendor/bin/phinx seed:run
migrate:
	vendor/bin/phinx migrate