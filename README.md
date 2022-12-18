[![Maintainability](https://api.codeclimate.com/v1/badges/3e79f811702828a15c4f/maintainability)](https://codeclimate.com/repos/5fbba591e9717c26f500064f/maintainability)

[![Test Coverage](https://api.codeclimate.com/v1/badges/3e79f811702828a15c4f/test_coverage)](https://codeclimate.com/repos/5fbba591e9717c26f500064f/test_coverage)

# todonotes

To Do Notes API project.

# Install Steps

From the projects root directory:

**Note:** *Only required once.*

1. Enter `cp .env.example .env` in your terminal to create your env.
1. Install the latest versions for `docker` and `docker-compose`.
1. Enter `composer install` in your terminal to pull in vendor packages.

# Running Steps

From the projects root directory:

**Note:** *Required always.*

1. Enter `make up` in your terminal to start the service containers.
1. Enter `make db.fresh` in your terminal to migrate a fresh DB
    instance and populate seed data.

    **Note:** *Skip to preserve your existing database.*
1. App now ready for development / testing...
1. When finished development / testing enter `make down` in your terminal
    to power down the docker containers gracefully.

# Access / Verifying

Use the guides below to ensure your local setup was successful and
for continued usage during development.

## General

All of the apps containers should now be running on your localhost
<http://127.0.0.1>.

PORTS for each container can be specified in the `.env`.

Refer to the list of all services and their default ports below:

```txt
NGINX_PORT=9909
DB_PORT=5432
REDIS_PORT=6379
```

You may set a `PORT_PREFIX` (empty by default) in the `.env` if needed.
The `SERVICE_PORT_NUM` in the example below should be replaced with one
of the values above that you wish to access.

- To access any service use <http://127.0.0.1:{PORT_PREFIX}{SERVICE_PORT_NUM}>.

## Container Specific

A guide for accessing and verifying each of the major containers is
provided below. Use this to ensure your environment is properly configured
before beginning development.

**Note**: *For the sections below, replace the port `.env` variables with the
values that you have set for them.*

## App

The primary Laravel Lumen app container, where the bulk of development will be
done.

1. Open <http://127.0.0.1:{PORT_PREFIX}{NGINX_PORT}/status> in your web browser,
    should return:

```json
{
    "status": "up",
    "release": "local",
    "env": "local",
    "details": "local - todonotes - {CURRENT_DATE}"
}
```

## Database

Verify your database connection using a SQL client. The suggested SQL client
at is **DBeaver** <https://dbeaver.io/>.

### SQL Client Setup

1. Create a new connection in your SQL client, choose PostgreSQL as the driver.
1. For "Host" enter `127.0.0.1`.
1. For "Port" enter `DB_PORT` you have set in your `.env`.
1. For "Database" enter `DB_DATABASE` you have set in your `.env`.
1. For "User" enter `DB_USERNAME` you have set in your `.env`.
1. For "Password" enter the `DB_PASSWORD` you have set in your `.env`.
1. Test connection (should be successful).
1. Label the connection as you wish and add.

# Composer Commands

## Testing

- `composer test:all` - Run all tests.
- `composer test:unit` - Run unit tests.
- `composer test:integration` - Run integration tests.
- `composer test:path` - Run all tests that are inside a directory.
    Expects path argument. Example: `composer test:path tests/unit`
- `composer test:filter` - Filter tests. Often used to run individual tests.
    Expects filter argument. Example: `composer test:filter MyTest`

# Make Commands

We are utilizing a number of Make commands to help with managing our app
through Docker.
For the full list see `Makefile` in the projects root directory.

## General Usage

- `make up` - This builds, (re)creates, starts containers required for
    testing/running this project.
- `make down` - Powers off containers and networks created by docker for
    when finished testing/running this project.
- `make build` - (Re)builds all services, for if you change a services
    Dockerfile or the contents of its build directory.
- `make init` - Similar to `make up` but will (re)build all services
    before starting the containers.
- `make remake` - Powers off everything and destroys the volumes, rebuilds
    and restarts all services.
    **Note: This will reset your database completely.**
- `make restart` - Used to restart all services by running `make down` followed
    by `make up`.
- `make ps` - List docker containers.

## SSH

- `make app` - SSH into the main application container bash shell.
- `make db` - SSH into the database container bash shell.
- `make sql` - SSH into the database container bash shell and login to the
    app database via PostgreSQL command line.
- `make redis` - SSH into the redis container bash shell.

## App Shortcuts

- Running php artisan commands is now simple: `./artisan_docker CMD`
  - Where `CMD` is any artisan command you want to run

**OLD Shortcuts:**

- `make db.fresh` - Drop all tables and re-run all migrations and seed
    the database.
- `make db.migrate` - Run all migrations that have no already been run.
- `make db.seed` - Seed the database.
- `make db.reset` - Rollback all database migrations.
- `make worker.queue` - Start processing jobs on the queue as a daemon.
- `make tests` - Run tests.
- `make cache-clear` - Clear composer and application cache.

## Test Shortcuts

- `make test` - Run all tests.
- `make test.unit` - Run unit tests.
- `make test.integration` - Run integration tests.
- `make test.path` - Run all tests that are inside a directory.
    Expects path argument. Example: `make test.path path=MyTest`
- `make test.filter` - Filter tests. Often used to run individual tests.
    Expects filter argument. Example: `make test.filter filter=MyTest`

## Log Shortcuts

- `make logs` - Print Docker logs.
- `make log.watch` - Follow Docker logs.
- `make log.{log_name}` - You can isolate the following specific logs:
    {web, app, db}.
- `make log.{log_name}-watch` - You can isolate and follow the following
    specific logs: {web, app, db}.
