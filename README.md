[![Maintainability](https://api.codeclimate.com/v1/badges/3e79f811702828a15c4f/maintainability)](https://codeclimate.com/repos/5fbba591e9717c26f500064f/maintainability)

[![Test Coverage](https://api.codeclimate.com/v1/badges/3e79f811702828a15c4f/test_coverage)](https://codeclimate.com/repos/5fbba591e9717c26f500064f/test_coverage)

# To Do Notes - API

A simple API to perform the following actions:
1. `GET http://127.0.0.1:9909/notes/` - Get all notes
1. `GET http://127.0.0.1:9909/notes/{identifier}` - Get a single note with Author ID, content, completion time, and its created and updated at timestamps
1. `POST http://127.0.0.1:9909/notes/` - Create a TODO notes with some content.
1. `PUT http://127.0.0.1:9909/notes/{identifier}?makeComplete=true` - Mark a TODO note as complete
1. `GET http://127.0.0.1:9909/notes/{identifier}?makeComplete=false` - Mark a TODO note as incomplete
1. `DELETE http://127.0.0.1:9909/notes/` - Delete a note


# Install Steps

From the root directory:

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
```

## Container Specific

A guide for accessing and verifying each of the major containers is
provided below. Use this to ensure your environment is properly configured
before beginning development.

## App

The primary Laravel Lumen app container, where the bulk of development is done.

1. Open <http://127.0.0.1{NGINX_PORT}/status> in your web browser,
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

# Make Commands

For the full list see `Makefile` in the projects root directory.

## General Usage

- `make up` - This builds, (re)creates, starts containers required for
    testing/running this project.
- `make down` - Powers off containers and networks created by docker for
    when finished testing/running this project.
- `make build` - (Re)builds all services, for if you change a services
    Dockerfile or the contents of its build directory.
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

## App Shortcuts

- `make db.fresh` - Drop all tables and re-run all migrations and seed
    the database.
- `make db.migrate` - Run all migrations that have no already been run.
- `make db.seed` - Seed the database.
- `make db.reset` - Rollback all database migrations.
- `make cache-clear` - Clear composer and application cache.

## Test Shortcuts

- `make test` - Run all tests.
- `make test.unit` - Run unit tests.