<?php

namespace App\Tests\Controller;

use App\Controller\LocationController;
use App\RickMortyClient\Character\CharacterClient;
use App\RickMortyClient\Character\CharacterCollection;
use App\RickMortyClient\Location\Location;
use App\RickMortyClient\Location\LocationClient;
use App\RickMortyClient\Location\LocationCollection;
use Monolog\Test\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LocationControllerTest extends TestCase
{
    public function testLocationIndex()
    {
        $locationClient = $this->getMockBuilder(LocationClient::class)->disableOriginalConstructor()->getMock();
        $characterClient = $this->getMockBuilder(CharacterClient::class)->disableOriginalConstructor()->getMock();

        $locationCollection = new LocationCollection([], 0);
        $locationClient->method('getAll')->with(0,9)->willReturn($locationCollection);
        $locationController = $this->getMockBuilder(LocationController::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$locationClient, $characterClient])
            ->onlyMethods(['render'])
            ->getMock();

        $locationController->method('render')
            ->with('location/index.html.twig', $this->anything())
            ->willReturnCallback(function($template, $params) use ($locationCollection){
                $this->assertSame($locationCollection, $params['locations']);
                $this->assertSame(1, $params['page']);
                $this->assertSame(9, $params['itemsPerPage']);
                return new Response();
            });
        $locationController->locationIndex($this->getMockBuilder(Request::class)->getMock());
    }

    public function testLocationShow()
    {
        $locationClient = $this->getMockBuilder(LocationClient::class)->disableOriginalConstructor()->getMock();
        $characterClient = $this->getMockBuilder(CharacterClient::class)->disableOriginalConstructor()->getMock();

        $location = new Location(2, "", "", "", []);
        $locationClient->method('get')->with(1383588489)->willReturn($location);

        $characterCollection = new CharacterCollection([], 0);
        $characterClient->method('getBulk')->willReturn($characterCollection);

        $locationController = $this->getMockBuilder(LocationController::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$locationClient, $characterClient])
            ->onlyMethods(['render'])
            ->getMock();

        $locationController->method('render')
            ->with('location/show.html.twig', $this->anything())
            ->willReturnCallback(function($template, $params) use ($location, $characterCollection){
                $this->assertSame($location, $params['location']);
                $this->assertSame($characterCollection, $params['residents']);
                return new Response();
            });

        $locationController->locationShow(1383588489);
    }
}
