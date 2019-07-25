# —— Inspired by ———————————————————————————————————————————————————————————————
# https://speakerdeck.com/mykiwi/outils-pour-ameliorer-la-vie-des-developpeurs-symfony?slide=47
# https://blog.theodo.fr/2018/05/why-you-need-a-makefile-on-your-project/

# Setup —————————————————————————————————————————————————————————————————————————
PROJECT  = sf4-ddd-user-admin
EXEC_PHP = php
SYMFONY  = $(EXEC_PHP) bin/console
COMPOSER = composer
.DEFAULT_GOAL := help

## —— Make file ————————————————————————————————————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Composer —————————————————————————————————————————————————————————————————
install: composer.lock ## Install vendors according to the current composer.lock file
	$(COMPOSER) install

update: composer.json ## Update vendors according to the current composer.json file
	$(COMPOSER) update

## —— Symfony ——————————————————————————————————————————————————————————————————
sf: ## List Symfony commands
	$(SYMFONY)

cc: ## Clear cache
	$(SYMFONY) c:c

warmup: ## Warmump the cache
	$(SYMFONY) cache:warmup

fix-perms: ## Fix permissions of all var files
	chmod -R 777 var/*

start: ## Start the local Symfony web server
	$(SYMFONY) server:start

stop: ## Stop the local Symfony web server
	$(SYMFONY) server:stop

assets: ## Install the assets with symlinks in the public folder (public)
	$(SYMFONY) assets:install web/ --symlink  --relative

purge: ## Purge cache and logs
	rm -rf var/cache/* var/logs/*

## —— Project ——————————————————————————————————————————————————————————————————
commands: ## Display all project specfic commands
	$(SYMFONY) list app

load-fixtures: ## Build the db, control the schema validity, load fixtures and check the migration status
	$(SYMFONY) doctrine:database:create --if-not-exists
	$(SYMFONY) doctrine:schema:drop --force
	$(SYMFONY) doctrine:schema:create
	$(SYMFONY) doctrine:schema:validate
	$(SYMFONY) doctrine:migration:status

cs: ## Launch check style and static analysis
	./vendor/bin/php-cs-fixer --no-interaction --dry-run --diff -v fix

cs-fix: ## Executes cs fixer
	./vendor/bin/php-cs-fixer --no-interaction --diff -v fix

dc: ## Launch Deptrac to check relations and dependencies between DDD layers
	./vendor/bin/deptrac

test: phpunit.xml.dist ## Launch all functionnal and unit tests
	bin/phpunit --stop-on-failure

scanner: ## Launch sonar-scanner after phpunit
	bin/phpunit
	sonar-scanner
