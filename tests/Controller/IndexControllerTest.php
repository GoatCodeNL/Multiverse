<?php

namespace App\Tests\Controller;

use App\Controller\EpisodeController;
use App\Controller\IndexController;
use App\Controller\PlumbusController;
use App\RickMortyClient\Character\CharacterClient;
use App\RickMortyClient\Character\CharacterCollection;
use App\RickMortyClient\Episode\Episode;
use App\RickMortyClient\Episode\EpisodeClient;
use App\RickMortyClient\Episode\EpisodeCollection;
use App\RickMortyClient\Location\LocationClient;
use App\RickMortyIpsumClient\Client;
use App\Tests\Mocks\MockCache;
use Monolog\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexControllerTest extends TestCase
{
    public function testIndex()
    {
        $locationClient = new LocationClient(new MockHttpClient(), new MockCache());
        $ipsumClient = new Client(new MockHttpClient(new MockResponse('{"data":["myquote"]}')));

        $indexController = $this->getMockBuilder(IndexController::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$locationClient, $ipsumClient])
            ->onlyMethods(['render'])
            ->getMock();

        $indexController->method('render')
            ->willReturnCallback(function($template, $params){
                $this->assertSame("index/index.html.twig", $template);
                $this->assertSame("myquote", $params['quoteOfTheDay']);
                return new Response();
            });
        $indexController->index();
    }


    public function testRandomLocations()
    {
        $httpClient = new MockHttpClient(function ($method, $url) {
            $parsedUrl = parse_url($url);
            parse_str($parsedUrl['query'], $parsedQuery);
            $this->assertStringEndsWith('location', $parsedUrl['path']);
            return new MockResponse(file_get_contents(__DIR__ . '/../data/httpResponse/RickMortyApi/location_multiple_' . $parsedQuery['page'] . '.json'));
        });

        $locationClient = new LocationClient($httpClient, new MockCache());
        $ipsumClient = new Client(new MockHttpClient());

        $indexController = $this->getMockBuilder(IndexController::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$locationClient, $ipsumClient])
            ->onlyMethods(['redirectToRoute'])
            ->getMock();

        $indexController->method('redirectToRoute')
            ->willReturnCallback(function($route, $params){
                $this->assertSame("locationShow", $route);
                $this->assertArrayHasKey("id", $params);
                return new RedirectResponse("asd");
            });

        $indexController->randomLocation();
    }
}
