<?php

namespace App\Services;

use GuzzleHttp\Client;

class TrackingService
{
    protected $client;
    protected $apiKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = config('tracking.api_key');
    }

    public function trackShipping($resi)
    {
        $response = $this->client->post('https://api.tracking.com/track', [
            'headers' => [
                'key' => $this->apiKey
            ],
            'form_params' => [
                'resi' => $resi
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}