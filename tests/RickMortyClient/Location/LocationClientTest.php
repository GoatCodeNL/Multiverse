<?php

namespace App\Tests\RickMortyClient\Location;

use App\RickMortyClient\Character\CharacterClient;
use App\RickMortyClient\Dimension\Dimension;
use App\RickMortyClient\Location\LocationClient;
use App\Tests\Mocks\MockCache;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class LocationClientTest extends TestCase
{
    public function testGetAll()
    {
        $httpClient = new MockHttpClient(function ($method, $url) {
            $parsedUrl = parse_url($url);
            parse_str($parsedUrl['query'], $parsedQuery);
            $this->assertStringEndsWith('location', $parsedUrl['path']);
            return new MockResponse(file_get_contents(__DIR__ . '/../../data/httpResponse/RickMortyApi/location_multiple_' . $parsedQuery['page'] . '.json'));
        });

        $client = new LocationClient($httpClient, new MockCache());

        $response = $client->getAll(0, 10);
        $this->assertSame(40, $response->getItemCount());

        $this->assertSame(1, $response->current()->getId());
        $this->assertSame("Earth (C-137)", $response->current()->getName());
        $this->assertSame("Planet", $response->current()->getType());
        $this->assertSame("Dimension C-137", $response->current()->getDimension());
        $this->assertSame(Dimension::generateId($response->current()->getDimension()), $response->current()->getDimensionId());
        $this->assertSame([
            'https://rickandmortyapi.com/api/character/38',
            'https://rickandmortyapi.com/api/character/45'
        ], $response->current()->getResidents());
    }
}
