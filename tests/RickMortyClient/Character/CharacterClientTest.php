<?php

namespace App\Tests\RickMortyClient\Character;

use App\RickMortyClient\Character\CharacterClient;
use App\Tests\Mocks\MockCache;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class CharacterClientTest extends TestCase
{
    public function testGetAll()
    {
        $httpClient = new MockHttpClient(function ($method, $url) {
            $parsedUrl = parse_url($url);
            parse_str($parsedUrl['query'], $parsedQuery);
            return new MockResponse(file_get_contents(__DIR__ . '/../../data/httpResponse/RickMortyApi/character_multiple_' . $parsedQuery['page'] . '.json'));
        });

        $client = new CharacterClient($httpClient, new MockCache());

        $response = $client->getAll(15, 10);
        $this->assertSame(40, $response->getItemCount());

        $this->assertSame(16, $response->current()->getId());
        $this->assertSame("Amish Cyborg", $response->current()->getName());
        $this->assertSame("Dead", $response->current()->getStatus());
        $this->assertSame("Alien", $response->current()->getSpecies());
        $this->assertSame("Parasite", $response->current()->getType());
        $this->assertSame("Male", $response->current()->getGender());
        $this->assertEquals((object)["name" => "unknown", "url" => ""], $response->current()->getOrigin());
        $this->assertSame(0, $response->current()->getOriginId());
        $this->assertEquals((object)["name" => "Earth (Replacement Dimension)", "url" => "https://rickandmortyapi.com/api/location/20"], $response->current()->getLocation());
        $this->assertSame(20, $response->current()->getLocationId());
        $this->assertSame("https://rickandmortyapi.com/api/character/avatar/16.jpeg", $response->current()->getImage());
        $this->assertSame(["https://rickandmortyapi.com/api/episode/15"], $response->current()->getEpisode());

        // skip to the last one
        foreach ($response as $character) {
        }

        $this->assertSame(10, $response->key());
    }


    public function testGet()
    {
        $httpClient = new MockHttpClient(function ($method, $url) {
            $this->assertStringEndsWith("character/16", $url);
            return new MockResponse(file_get_contents(__DIR__ . '/../../data/httpResponse/RickMortyApi/character_single_16.json'));
        });

        $client = new CharacterClient($httpClient, new MockCache());

        $response = $client->get(16);

        $this->assertSame(16, $response->getId());
        $this->assertSame("Amish Cyborg", $response->getName());
        $this->assertSame("Dead", $response->getStatus());
        $this->assertSame("Alien", $response->getSpecies());
        $this->assertSame("Parasite", $response->getType());
        $this->assertSame("Male", $response->getGender());
        $this->assertEquals((object)["name" => "unknown", "url" => ""], $response->getOrigin());
        $this->assertSame(0, $response->getOriginId());
        $this->assertEquals((object)["name" => "Earth (Replacement Dimension)", "url" => "https://rickandmortyapi.com/api/location/20"], $response->getLocation());
        $this->assertSame(20, $response->getLocationId());
        $this->assertSame("https://rickandmortyapi.com/api/character/avatar/16.jpeg", $response->getImage());
        $this->assertSame(["https://rickandmortyapi.com/api/episode/15"], $response->getEpisode());
    }

    public function testGetBulk()
    {
        $httpClient = new MockHttpClient(function ($method, $url) {
            $this->assertStringEndsWith("character/16,17", $url);
            return new MockResponse(file_get_contents(__DIR__ . '/../../data/httpResponse/RickMortyApi/character_bulk_16_17.json'));
        });

        $client = new CharacterClient($httpClient, new MockCache());

        $response = $client->getBulk(
            [
                "https://rickandmortyapi.com/api/character/16",
                "https://rickandmortyapi.com/api/character/17"
            ]
        );

        $this->assertSame(2, $response->getItemCount());

        $this->assertSame(16, $response->current()->getId());
        $this->assertSame("Amish Cyborg", $response->current()->getName());
        $this->assertSame("Dead", $response->current()->getStatus());
        $this->assertSame("Alien", $response->current()->getSpecies());
        $this->assertSame("Parasite", $response->current()->getType());
        $this->assertSame("Male", $response->current()->getGender());
        $this->assertEquals((object)["name" => "unknown", "url" => ""], $response->current()->getOrigin());
        $this->assertSame(0, $response->current()->getOriginId());
        $this->assertEquals((object)["name" => "Earth (Replacement Dimension)", "url" => "https://rickandmortyapi.com/api/location/20"], $response->current()->getLocation());
        $this->assertSame(20, $response->current()->getLocationId());
        $this->assertSame("https://rickandmortyapi.com/api/character/avatar/16.jpeg", $response->current()->getImage());
        $this->assertSame(["https://rickandmortyapi.com/api/episode/15"], $response->current()->getEpisode());

        // skip to the last one
        foreach ($response as $character) {
        }

        $this->assertSame(2, $response->key());
    }


    public function testGetBulkWithSingleItem()
    {
        $httpClient = new MockHttpClient(function ($method, $url) {
            $this->assertStringEndsWith("character/16", $url);
            return new MockResponse(file_get_contents(__DIR__ . '/../../data/httpResponse/RickMortyApi/character_single_16.json'));
        });

        $client = new CharacterClient($httpClient, new MockCache());

        $response = $client->getBulk(
            [
                "https://rickandmortyapi.com/api/character/16",
            ]
        );

        $this->assertSame(1, $response->getItemCount());

        $this->assertSame(16, $response->current()->getId());
        $this->assertSame("Amish Cyborg", $response->current()->getName());

        // skip to the last one
        foreach ($response as $character) {
        }

        $this->assertSame(1, $response->key());
    }
    public function testGetBulkWithoutItem()
    {
        $httpClient = new MockHttpClient(function ($method, $url) {
            $this->assertFalse(true, "Request should not be executed");
        });

        $client = new CharacterClient($httpClient, new MockCache());

        $response = $client->getBulk(
            [
            ]
        );

        $this->assertSame(0, $response->getItemCount());

        // skip to the last one
        foreach ($response as $character) {
        }

        $this->assertSame(0, $response->key());
    }
}
