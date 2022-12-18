# Keep Empty
FORCE:
up:
	docker-compose up -d
down:
	docker-compose down --remove-orphans
build:
	docker-compose build --no-cache --force-rm
init:
	docker-compose up -d --build
# 	@make fresh
remake:
	@make destroy
	@make init
stop:
	docker-compose stop
restart:
	@make down
	@make up

app: FORCE
	docker-compose exec app bash
db: FORCE
	docker-compose exec database bash
sql: FORCE
	docker-compose exec database bash -c 'psql -d $$POSTGRES_DB -U $$POSTGRES_USER'
redis: FORCE
	docker-compose exec redis redis-cli

laravel-install:
	docker-compose exec app composer create-project --prefer-dist laravel/laravel .
create-project:
	@make build
	@make up
	@make laravel-install
	php artisan key:generate
	docker-compose exec app php artisan storage:link
	@make fresh
install-recommend-packages:
	docker-compose exec app composer require doctrine/dbal
	docker-compose exec app composer require --dev barryvdh/laravel-ide-helper
	docker-compose exec app composer require --dev beyondcode/laravel-dump-server
	docker-compose exec app composer require --dev barryvdh/laravel-debugbar
	docker-compose exec app composer require --dev roave/security-advisories:dev-master
	docker-compose exec app php artisan vendor:publish --provider="BeyondCode\DumpServer\DumpServerServiceProvider"
	docker-compose exec app php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"


destroy:
	docker-compose down --volumes --remove-orphans
# 	docker-compose down --rmi all --volumes --remove-orphans
# 	@make docker-images-prune
destroy-volumes:
	docker-compose down --volumes --remove-orphans
docker-images-prune:
	docker image prune -f
ps:
	docker-compose ps

logs:
	docker-compose logs
log.watch:
	docker-compose logs --follow
log.web:
	docker-compose logs web
log.web-watch:
	docker-compose logs --follow web
log.app:
	docker-compose logs app
log.app-watch:
	docker-compose logs --follow app
log.db:
	docker-compose logs db
log.db-watch:
	docker-compose logs --follow db

test:
	docker-compose exec app composer test:all
test.unit:
	docker-compose exec app composer test:unit
test.integration:
	docker-compose exec app composer test:integration
test.path:
	docker-compose exec app composer test:path tests/$(path)
test.filter:
	docker-compose exec app composer test:filter $(filter)

db.fresh:
	docker-compose exec app php artisan migrate:fresh --seed
db.migrate:
	docker-compose exec app php artisan migrate
db.seed:
	docker-compose exec app php artisan db:seed
db.reset:
	docker-compose exec app php artisan migrate:reset
rollback-test:
	docker-compose exec app php artisan migrate:fresh
	docker-compose exec app php artisan migrate:refresh
worker.queue:
	docker-compose exec app php artisan queue:work --sleep=3 --tries=3
tinker:
	docker-compose exec app php artisan tinker
unit: FORCE
	docker-compose exec app composer unit
cache:
	docker-compose exec app composer dump-autoload -o
	docker-compose exec app php artisan cache:clear
cache-clear:
	docker-compose exec app composer clear-cache
	docker-compose exec app php artisan cache:clear
