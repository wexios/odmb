<?php

class MovieController
{
    private string $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function searchMovies($key)
    {
        if (empty($key)) {
            echo json_encode(["error" => "Search key is required"]);
            return;
        }

        $movies = $this->fetchFromApi($key);
        echo json_encode($movies);
    }

    private function fetchFromApi($key)
    {
        $url = "http://www.omdbapi.com/?apikey={$this->apiKey}&s=" . urlencode($key);

        $response = @file_get_contents($url);

        if ($response === false) {
            return ["error" => "Failed to fetch data from OMDB"];
        }

        $data = json_decode($response, true);
        return $data["Search"] ?? ["error" => "No movies found"];
    }
}

// Initialize and handle the request
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$config = require $ROOT.'/env.php'; // Ensure your API key is stored in env.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

$apiKey = $config["API_KEY"]; // Assuming $api is defined in env.php
$controller = new MovieController($apiKey);
$key = $_GET["k"] ?? "";
$controller->searchMovies($key);
