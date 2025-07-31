start:
	sudo docker compose -f docker/compose.yml up -d
	sleep 2
	xfce4-terminal --tab --title="PHP" --command="docker compose -f docker/compose.yml exec -it php-fpm /bin/bash"
	xfce4-terminal --tab --title="Node" --command="docker compose -f docker/compose.yml exec -it node /bin/bash"
	xfce4-terminal --tab --title="MySQL" --command="docker compose -f docker/compose.yml exec -it db /bin/bash"

start-debug:
	sudo docker compose -f docker/compose.yml up

stop:
	sudo docker compose -f docker/compose.yml down

build:
	sudo docker compose -f docker/compose.yml build --build-arg userId=$(shell id -u) --build-arg groupId=$(shell id -g)

install: build
	cp app/.env.example app/.env
	sudo docker compose -f docker/compose.yml up -d
	sleep 2
	sudo docker compose -f docker/compose.yml exec -T php-fpm composer install --no-interaction --prefer-dist
	sudo docker compose -f docker/compose.yml exec -T php-fpm php artisan key:generate
	sudo docker compose -f docker/compose.yml exec -T php-fpm php artisan jwt:secret
	sudo docker compose -f docker/compose.yml exec -T php-fpm php artisan migrate
	sudo docker compose -f docker/compose.yml exec -T node npm install
