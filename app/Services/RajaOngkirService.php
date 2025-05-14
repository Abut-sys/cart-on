<?php

namespace App\Services;

use GuzzleHttp\Client;

class RajaOngkirService
{
    protected $client;
    protected $apiKey;
    protected $originCity;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = config('rajaongkir.api_key');
        $this->originCity = config('rajaongkir.origin_city');
    }

    public function getShippingCost($origin, $destination, $weight, $courier)
    {
        $response = $this->client->post('https://api.rajaongkir.com/starter/cost', [
            'headers' => [
                'key' => $this->apiKey
            ],
            'form_params' => [
                'origin' => $this->originCity,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}