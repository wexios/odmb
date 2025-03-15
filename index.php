<?php

// Enable error reporting (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the requested URL path
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$query_params = $_GET;

// Define routes
if ($request_uri === '/') {
    require_once __DIR__ . '/app/views/movies/index.php';
    exit;
}

if ($request_uri === '/api/movies/search' && isset($query_params['k'])) {
    require_once __DIR__ . '/app/controllers/MovieController.php';
    exit;
}

// If no matching route is found, return 404
http_response_code(404);
echo json_encode(["error" => "Not Found"]);
exit;
