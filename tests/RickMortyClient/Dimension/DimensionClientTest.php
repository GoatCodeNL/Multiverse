<?php

namespace App\Tests\RickMortyClient\Dimension;

use App\RickMortyClient\Dimension\DimensionClient;
use App\RickMortyClient\Location\LocationClient;
use App\Tests\Mocks\MockCache;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class DimensionClientTest extends TestCase
{
    public function testGetAll()
    {
        $httpClient = new MockHttpClient(function ($method, $url) {
            $parsedUrl = parse_url($url);
            parse_str($parsedUrl['query'], $parsedQuery);
            return new MockResponse(file_get_contents(__DIR__ . '/../../data/httpResponse/RickMortyApi/location_multiple_' . $parsedQuery['page'] . '.json'));
        });

        $client = new DimensionClient(new MockCache(), new LocationClient($httpClient, new MockCache()));

        $response = $client->getAll(0, 10);
        $this->assertSame(14, $response->getItemCount());
        $this->assertSame(1383588489, $response->current()->getId());
        $this->assertSame("Dimension C-137", $response->current()->getName());
        $this->assertCount(3, $response->current()->getLocations());
        $this->assertCount(21, $response->current()->getResidents());
        $this->assertSame("https://rickandmortyapi.com/api/character/38", $response->current()->getResidents()[0]);

        // skip to the last one
        foreach ($response as $dimension) {
        }

        $this->assertSame(10, $response->key());
    }


    public function testGet()
    {
        $httpClient = new MockHttpClient(function ($method, $url) {
            $parsedUrl = parse_url($url);
            parse_str($parsedUrl['query'], $parsedQuery);
            return new MockResponse(file_get_contents(__DIR__ . '/../../data/httpResponse/RickMortyApi/location_multiple_' . $parsedQuery['page'] . '.json'));
        });

        $client = new DimensionClient(new MockCache(), new LocationClient($httpClient, new MockCache()));

        $response = $client->get(1383588489);

        $this->assertSame(1383588489, $response->getId());
        $this->assertSame("Dimension C-137", $response->getName());
        $this->assertCount(3, $response->getLocations());
        $this->assertCount(21, $response->getResidents());
        $this->assertSame("https://rickandmortyapi.com/api/character/38", $response->getResidents()[0]);
    }
    public function testGetUnknownDimension()
    {
        $httpClient = new MockHttpClient(function ($method, $url) {
            $parsedUrl = parse_url($url);
            parse_str($parsedUrl['query'], $parsedQuery);
            return new MockResponse(file_get_contents(__DIR__ . '/../../data/httpResponse/RickMortyApi/location_multiple_' . $parsedQuery['page'] . '.json'));
        });

        $client = new DimensionClient(new MockCache(), new LocationClient($httpClient, new MockCache()));

        $this->expectException(\Exception::class);
        $response = $client->get(12345678);
    }

    public function testGetBulk()
    {
        $httpClient = new MockHttpClient();

        $client = new DimensionClient(new MockCache(), new LocationClient($httpClient, new MockCache()));

        $this->expectException(\Exception::class);
        $response = $client->getBulk(
            [
                "https://rickandmortyapi.com/api/dimension/16",
                "https://rickandmortyapi.com/api/dimension/17"
            ]
        );
    }

}
