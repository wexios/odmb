<?php

// Enable error reporting (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the requested URL path
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$query_params = $_GET;

// Define routes

// Serve the home page
if ($request_uri === '/') {
    require_once __DIR__ . '/app/views/movies/index.php';
    exit;
}

if ($request_uri === '/auth') {
    require_once __DIR__ . '/app/views/auth/index.php';
    exit;
}

// Movie search route
if ($request_uri === '/api/movies/search' && isset($query_params['k'])) {
    require_once __DIR__ . '/app/controllers/MovieController.php';
    exit;
}

// Authentication routes
if (strpos($request_uri, '/api/auth') === 0) {
    require_once __DIR__ . '/app/controllers/AuthController.php';
    exit;
}

// Favorite movie routes
if (strpos($request_uri, '/api/favorites') === 0) {
    require_once __DIR__ . '/app/controllers/FavoriteController.php';
    exit;
}

// If no matching route is found, return 404
http_response_code(404);
echo json_encode(["error" => "Not Found"]);
exit;
