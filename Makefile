down:
	docker-compose -f docker/dev/docker-compose.yml down --remove-orphans
shell:
	docker-compose -f docker/dev/docker-compose.yml exec -u ${UID}:${UID} app sh
up:
	docker-compose -f docker/dev/docker-compose.yml up --build --remove-orphans -d
up-f:
	docker-compose -f docker/dev/docker-compose.yml up --build --remove-orphans
