# PHP Project Setup

## Environment Configuration

Create a configuration file `config.php` and define the necessary environment variables:

```php
<?php
return [
    'API_KEY' => '',
    'DB_HOST' => '',
    'DB_USER' => '',
    'DB_PASS' => '',
    'DB_NAME' => ''
];
```

Fill in the required details as per your database and API settings.

## Running the Project

To start the PHP development server, run the following command:

```sh
php -S localhost:8000
```

This will start the server on `http://localhost:8000/`.

## Notes
- Ensure PHP is installed on your system.
- Update the environment configuration in `config.php` before running the project.
- Access your application via a web browser using `http://localhost:8000/`.

