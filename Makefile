#!/usr/bin/make
# Makefile readme (ru): <http://linux.yaroslavl.ru/docs/prog/gnu_make_3-79_russian_manual.html>
# Makefile readme (en): <https://www.gnu.org/software/make/manual/html_node/index.html#SEC_Contents>

# [ -f (pwd)/.env ] && include .env || include .env.example;
include .env
export

SHELL = /bin/sh

BACKUP_NAME := init_dump
CURRENT_TIME := $(shell date +"%m-%d-%Y")
DOMAIN_SITE := $(DOMAIN_SITE)

docker_bin := $(shell command -v docker 2> /dev/null)
docker_compose_bin := $(shell command -v docker-compose 2> /dev/null)
MYSQL_CONTAINER_NAME := $(shell docker ps --filter name=mysql --format {{.Names}})
APP_CONTAINER_NAME := $(shell docker ps --filter name=app --format {{.Names}})

.DEFAULT_GOAL := help


help: ## Show this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
	@echo "\n  Allowed for overriding next properties:\n\n\
		Usage example:\n\
			make up"

rsync:
	@echo "Starting rcync"
	rsync -avzhe "ssh" --exclude-from='./boilerplate/rsync-filter-$(CMS_TYPE_PROJECT)' $(SSH_USER)@$(SSH_SERVER):$(PATH_TO_PUBLIC_HTML) ./www/

rsync-without:
	@echo "Starting rcync"
	rsync -avzhe "ssh" --exclude-from='./boilerplate/rsync-filter-$(CMS_TYPE_PROJECT)-without' $(SSH_USER)@$(SSH_SERVER):$(PATH_TO_PUBLIC_HTML) ./www/


first-init: up init-git rsync restore-first

init-with-down: down rsync up restore-first

init: rsync up restore-first

init-git:
	@cat ./boilerplate/.gitignore-$(CMS_TYPE_PROJECT) >> .gitignore

## --- [ MySQL ] -------------------------------------------------------------------------------------------------

restore-first: download-dump create-database restore-mysql set-config

set-config:
	@if [ "$(CMS_TYPE_PROJECT)" == "wordpress" ]; then \
		cp -f ./boilerplate/wp-config.php ./www/wp-config.php; \
	elif [ "$(CMS_TYPE_PROJECT)" == "bitrix" ]; then \
	  	cp -f ./boilerplate/.settings.php ./www/bitrix/.settings.php; \
	fi;

download-dump:
	@echo "Starting download database dump"
	@ssh $(SSH_USER)@$(SSH_SERVER) "mysqldump -h localhost -u $(SERVER_DATABASE_USER) -p$(SERVER_DATABASE_PASS) $(SERVER_DATABASE_NAME) " > ./boilerplate/data/backup/$(BACKUP_NAME).sql
	@sed -i '' 's,http://${DOMAIN_SITE},http://localhost,g' ./boilerplate/data/backup/$(BACKUP_NAME).sql
	@sed -i '' 's,https://${DOMAIN_SITE},http://localhost,g' ./boilerplate/data/backup/$(BACKUP_NAME).sql
	@sed -i '' 's,${DOMAIN_SITE},localhost,g' ./boilerplate/data/backup/$(BACKUP_NAME).sql

create-database:
	@sleep 5s
	@docker exec -it $(shell docker ps --filter name=mysql --format {{.Names}}) sh -c "echo 'DROP DATABASE IF EXISTS $(MYSQL_DATABASE); CREATE DATABASE $(MYSQL_DATABASE) CHARACTER SET utf8 COLLATE utf8_unicode_ci;' | mysql -u root -p$(MYSQL_ROOT_PASSWORD)"

restore-mysql:
	@echo "Starting restore MySQL database"
	@sleep 5s
	@docker exec -it $(shell docker ps --filter name=mysql --format {{.Names}}) sh -c "mysql -u root -p$(MYSQL_ROOT_PASSWORD) $(MYSQL_DATABASE) < /backup/$(BACKUP_NAME).sql"

backup-mysql:
	@echo "Starting backup MySQL database"
	@docker exec -it $(MYSQL_CONTAINER_NAME) sh -c "mkdir -p /backup/$(CURRENT_TIME)" \
		&& docker exec -it $(MYSQL_CONTAINER_NAME) \
			sh -c "mysqldump -u root -p$(MYSQL_ROOT_PASSWORD) $(MYSQL_DATABASE) > /backup/$(CURRENT_TIME)/$(BACKUP_NAME).sql"

# --- [ Docker ] -------------------------------------------------------------------------------------------------

build: ## rebuild all containers
	$(docker_compose_bin) build

up: build  ## rebuild and up all containers
	$(docker_compose_bin) up -d --remove-orphans
	@docker exec -it $(APP_CONTAINER_NAME) sh -c "alias wp='wp --allow-root'"

down: ## down all containers
	$(docker_compose_bin) down

restart: build down up ## rebuild and restart all containers

stop: ## stop all containers
	@$(docker_bin) ps -aq | xargs $(docker_bin) stop

app-composer-install: ## front composer install
	@cd ./www && composer install

app-composer: ## front composer update
	@cd ./www && composer update
