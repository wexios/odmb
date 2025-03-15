<?php

namespace App\Services;

use GuzzleHttp\Client;

class OMDBService
{
    protected $client;
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function searchMovies($query)
    {
        $response = $this->client->request('GET', 'http://www.omdbapi.com/', [
            'query' => [
                'apikey' => $this->apiKey,
                's' => $query
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}