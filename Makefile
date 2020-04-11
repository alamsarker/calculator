FPM=calculator_fpm_1

.PHONY : start fpm phpunit composer migrate

start:
	docker-compose down
	docker-compose up

fpm:
	docker exec -it $(FPM) bash

phpunit:
	docker exec -it $(FPM) bash -c 'php bin/phpunit'

composer:
	docker exec -it $(FPM) bash -c 'composer install'

migrate:
	docker exec -it $(FPM) bash -c 'php bin/console doctrine:migrations:migrate'

stop:
	docker-compose down
