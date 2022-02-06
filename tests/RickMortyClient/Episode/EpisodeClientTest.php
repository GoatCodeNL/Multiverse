<?php

namespace App\Tests\RickMortyClient\Episode;

use App\RickMortyClient\Character\CharacterClient;
use App\RickMortyClient\Dimension\Dimension;
use App\RickMortyClient\Episode\EpisodeClient;
use App\Tests\Mocks\MockCache;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class EpisodeClientTest extends TestCase
{
    public function testGetAll()
    {
        $httpClient = new MockHttpClient(function ($method, $url) {
            $parsedUrl = parse_url($url);
            parse_str($parsedUrl['query'], $parsedQuery);
            $this->assertStringEndsWith('episode', $parsedUrl['path']);
            return new MockResponse(file_get_contents(__DIR__ . '/../../data/httpResponse/RickMortyApi/episode_multiple_' . $parsedQuery['page'] . '.json'));
        });

        $client = new EpisodeClient($httpClient, new MockCache());

        $response = $client->getAll(0, 10);
        $this->assertSame(20, $response->getItemCount());

        $this->assertSame(1, $response->current()->getId());
        $this->assertSame("Pilot", $response->current()->getName());
        $this->assertSame("December 2, 2013", $response->current()->getAirDate());
        $this->assertSame("S01E01", $response->current()->getEpisodeCode());
        $this->assertSame([
            'https://rickandmortyapi.com/api/character/1',
            'https://rickandmortyapi.com/api/character/2'
        ], $response->current()->getCharacters());
    }
}
