<?php

namespace App\Tests\RickMortyIpsumClient;

use App\RickMortyIpsumClient\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ClientTest extends TestCase
{

    public function testGetOne()
    {
        $testString = "Wubba Lubba Dub Dub!";
        $httpClient = new MockHttpClient(function ($method, $url) use ($testString) {
            $parsedUrl = parse_url($url);
            parse_str($parsedUrl['query'], $parsedQuery);

            $this->assertSame("1", $parsedQuery['paragraphs']);
            $this->assertSame("1", $parsedQuery['quotes']);
            return new MockResponse('{"data": ["' . $testString . '"]}');
        });
        $client = new Client($httpClient);

        $response = $client->getOne();

        $this->assertSame($testString, $response);
    }


    public function testGet()
    {
        $testStrings = [
            "Wubba Lubba Dub Dub!",
            "Well I don't like your unemployed genes in my grandchildren, Jerry.",
            "We all wanna die, we're meeseeks!"
        ];
        $httpClient = new MockHttpClient(function ($method, $url) use ($testStrings) {
            $parsedUrl = parse_url($url);
            parse_str($parsedUrl['query'], $parsedQuery);

            $this->assertSame("3", $parsedQuery['paragraphs']);
            $this->assertSame("2", $parsedQuery['quotes']);
            return new MockResponse('{"data": ' . json_encode($testStrings) . '}');
        });
        $client = new Client($httpClient);

        $response = $client->get(3, 2);

        $this->assertSame($testStrings, $response);
    }
}
