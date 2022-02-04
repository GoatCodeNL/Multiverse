<?php

namespace App\RickMortyIpsumClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get random quotes. Each paragraph returned as array item.
     *
     * @param int $paragraphs Number of paragraphs
     * @param int $quotes Number of quotes per paragraph
     * @return array
     */
    public function get(int $paragraphs, int $quotes): array
    {
        $response = $this->client->request(
            'GET',
            sprintf("http://loremricksum.com/api/?paragraphs=%d&quotes=%d", $paragraphs, $quotes)
        );

        $content = json_decode($response->getContent());
        return $content->data;
    }

    public function getOne(): string
    {
        return $this->get(1,1)[0];
    }
}
