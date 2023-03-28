<?php

namespace models;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GorestAPI
{
    private Client $client;
    private array $headers;

    public function __construct()
    {
        $this->initializeClient();
    }

    private function initializeClient(): void
    {
        $this->headers = [
            'Authorization' => "Bearer " . $_ENV['GOREST_API_KEY'],
            'Content-Type' => 'application/json'
        ];
        $this->client = new Client(['base_uri' => 'https://gorest.co.in/public/v2/users/']);
    }

    public function getRecords(int $page, int $limit): array
    {
        try {
            $response = $this->client->request("GET", "?page=$page&per_page=$limit");
            $data = $response->getBody()->getContents();
            $decodedData = json_decode($data, true);
            if (empty($decodedData)) {
                return [['error' => 'There are no more records to load'], 404];
            } else {
                return [$data, $response->getStatusCode()];
            }
        } catch (GuzzleException $e) {
            return [['error' => $e->getMessage()], $e->getCode()];
        }
    }

    public function getOne(int $id): array
    {
        try {
            $response = $this->client->get("$id");
            $data = $response->getBody()->getContents();
            return [$data, $response->getStatusCode()];
        } catch (GuzzleException $e) {
            $response = ["error" => $e->getMessage()];
            return [$response, $e->getCode()];
        }
    }

    public function create(array $params): array
    {
        try {
            $response = $this->client->post('', [
                'headers' => $this->headers,
                'body' => json_encode($params)
            ]);
            return [$response->getBody()->getContents(), $response->getStatusCode()];
        } catch (GuzzleException $e) {
            $response = ["error" => $e->getMessage()];
            return [$response, $e->getCode()];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            $response = $this->client->patch("$id", [
                'headers' => $this->headers,
                'body' => json_encode($data)
            ]);
            return [$response->getBody()->getContents(), $response->getStatusCode()];
        } catch (GuzzleException $e) {
            return [['error' => $e->getMessage()], $e->getCode()];
        }
    }

    public function deleteOne(int $id): array
    {
        try {
            $this->client->delete("$id", [
                'headers' => $this->headers
            ]);
            return [[], 204];
        } catch (GuzzleException $e) {
            return [['error' => $e->getMessage()], $e->getCode()];
        }
    }

    public function deleteMultiple(array $ids): array
    {
        if (empty($ids)) {
            return [['error' => 'Invalid input'], 400];
        } else {
            try {
                foreach ($ids as $id) {
                    $this->client->delete("$id", [
                        'headers' => $this->headers
                    ]);
                }
                return [[], 204];
            } catch (GuzzleException $e) {
                return [['error' => $e->getMessage()], $e->getCode()];
            }
        }
    }
}