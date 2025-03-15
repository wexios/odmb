<?php
$env = parse_ini_file('.env');

if ($env === false) {
    throw new Exception("Error reading .env file");
}

$dbHost = $env['DB_HOST'];
$dbUser = $env['DB_USER'];
$dbPass = $env['DB_PASS'];
$dbName = $env['DB_NAME'];
$apiKey = $env['API_KEY'];

