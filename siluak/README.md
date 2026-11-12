# SILUAK (CodeIgniter 4)

This is the parallel CodeIgniter 4 application for SILUAK. The original Laravel
application remains in the sibling `siluak` directory.

## Run with Docker

Requirements: Docker Desktop with Docker Compose.

1. In PowerShell, open this folder and create the local configuration files:

   ```powershell
   Copy-Item env .env
   Copy-Item .env.docker.example .env.docker
   ```

2. Edit `.env.docker` and set unique local database passwords. In `.env`, set
   the application and database settings to match:

   ```ini
   CI_ENVIRONMENT = development
   app.baseURL = 'http://localhost:8080/'
   database.default.hostname = db
   database.default.database = siluak
   database.default.username = siluak
   database.default.password = 'change-this-local-password'
   database.default.DBDriver = MySQLi
   database.default.port = 3306
   ```

   If you changed the database name or username in `.env.docker`, update those
   values in `.env` too. Keep `.env` and `.env.docker` private; do not use the
   example passwords outside local development.

3. Build and start the app and its MariaDB database:

   ```powershell
   docker compose --env-file .env.docker up --build -d
   ```

4. Create the tables and open **http://localhost:8080**:

   ```powershell
   docker compose exec app php spark migrate --all
   ```

   To create the first admin in a new database, set `SILUAK_ADMIN_EMAIL` and
   `SILUAK_ADMIN_PASSWORD` in `.env`, then run:

   ```powershell
   docker compose exec app php spark db:seed DatabaseSeeder
   ```

   Existing Laravel data is not copied into the bundled database. To reuse an
   existing database, configure `.env` to connect to it instead; back it up and
   confirm the target before running migrations.

Stop the containers with `docker compose --env-file .env.docker down`. Database
and uploaded files persist in Docker volumes. To remove the database and
uploads as well, `docker compose --env-file .env.docker down --volumes` deletes
that stored data.

## Run without Docker

Requirements: PHP 8.2+, Composer, MySQL/MariaDB (or another configured database
driver), and PHP `intl`, `mbstring`, `zip`, and `gd` extensions.

## Setup

1. Copy `env` to `.env`.
2. Set `app.baseURL` and the database connection values in `.env`. Use the same
   database as Laravel to access existing users and requests; do not copy
   production credentials into source control.
3. Install dependencies with `composer install`.
4. Run `php spark migrate --all`. The migration creates the existing SILUAK
   domain tables when absent and does not add fields to existing tables.
5. Point the web server document root to `public/`, or run `php spark serve`.

For SQLite, set `database.default.DBDriver = SQLite3` and point
`database.default.database` to the existing SQLite file. For MySQL/MariaDB,
configure hostname, database, username, and password in `.env`. Confirm the
selected connection before running migrations.

The app does not create an administrator with a default password. Existing
Laravel user records can be used when the configured database is shared.
For a new database, set `SILUAK_ADMIN_EMAIL` and `SILUAK_ADMIN_PASSWORD` in
`.env`, then run `php spark db:seed DatabaseSeeder` to create the first admin.

## Tests

The tests use the in-memory SQLite configuration. If `sqlite3` is installed
but not enabled in the CLI PHP configuration, run:

```powershell
php -d extension=sqlite3 vendor/bin/phpunit --no-coverage
```

## Migration notes

- Database table and column names are kept compatible with the Laravel schema.
- File uploads are stored under `writable/uploads`; back up and migrate those
  files separately when changing storage locations.
- Enable PHP `zip` and `gd` in the web-server PHP configuration before using
  XLSX exports.
- Laravel Blade templates and the Laravel Vite plugin are not runtime
  dependencies of this application.
