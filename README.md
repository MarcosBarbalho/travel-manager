# Travel Manager

## Requirements

- Git
- docker: https://www.docker.com/
- docker-compose: https://docs.docker.com/compose/ (prefer installing the plugin version if not already installed)

## Docker Image Build

After cloning the repository to your machine, you will need to perform the initial environment setup. Follow the steps below:

1. `docker compose pull` to download the required images
2. `docker compose build --no-cache` to build the images
3. `docker compose up -d --wait` to start the containers

## Configuration

During the steps below, the command `docker compose run api` will be used multiple times. To avoid repetition, it will be abbreviated as `dcra`.

### Environment Setup

1. Copy the `.env.example` file to `.env` and edit it as needed
   - `cp .env.example .env`
2. Run `dcra composer install` to install project dependencies
3. Generate the Laravel encryption key with `dcra php artisan key:generate`
4. Generate the JWT secret with `dcra php artisan jwt:secret`
5. Run migrations and seed the database with `dcra php artisan migrate --seed`
6. Grant write permissions to the containers in the development environment with
   `dcra chown -R www-data:www-data /var/www/storage`

### Accessing the Database

This project includes a container with Adminer, making database access straightforward:

1. With the project running, access http://localhost:9090/
2. Use the following credentials on the login screen:
    - System: MySQL
    - Server: db
    - Username: sandbox
    - Password: sandbox
    - Database: sandbox

### Running Tests

Automated tests in this project were implemented using PHPUnit and can be found in the `/tests` directory.

To run the tests:

1. From the project root directory, execute:
   - `docker compose run api vendor/bin/phpunit tests/`

This command runs all tests. To execute a specific test, provide the full relative path to the test file.

### Stopping the Containers

To stop the containers, run the following command from the project root:

- `docker compose down --remove-orphans`

### Developer Notes

1. If you followed the setup instructions and ran the seed, the default users are:
    - Internal 1:
        - Email: a@a.com
        - Password: password
    - Internal 2:
        - Email: b@a.com
        - Password: password

2. This project uses JWT authentication. Except for `/login`, `/register`, and `/ping`, all routes require a JWT token.
   - Simply log in or register a user and use the `token` attribute from the response as the authentication key for other routes.

3. The Docker image configuration used in this project was created specifically for it, keeping the setup simple and lightweight.

4. If you use Insomnia to test API requests, you can find an importable file with all mapped routes in:
   `/storage/requests/InsomniaJsons`

## Recommended Reading

For a better understanding of this project, it is recommended to research any unfamiliar technical terms, especially:

- docker: https://www.docker.com/
- docker-compose: https://docs.docker.com/compose/
- composer: https://getcomposer.org/
- Adminer: https://www.adminer.org/
- PHPUnit: https://docs.phpunit.de/en/11.4/
- Insomnia: https://insomnia.rest/
