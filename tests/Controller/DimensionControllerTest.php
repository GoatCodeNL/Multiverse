<?php

namespace App\Tests\Controller;

use App\Controller\DimensionController;
use App\RickMortyClient\Character\CharacterClient;
use App\RickMortyClient\Character\CharacterCollection;
use App\RickMortyClient\Dimension\Dimension;
use App\RickMortyClient\Dimension\DimensionClient;
use App\RickMortyClient\Dimension\DimensionCollection;
use Monolog\Test\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DimensionControllerTest extends TestCase
{
    public function testDimensionIndex()
    {
        $dimensionClient = $this->getMockBuilder(DimensionClient::class)->disableOriginalConstructor()->getMock();
        $characterClient = $this->getMockBuilder(CharacterClient::class)->disableOriginalConstructor()->getMock();

        $dimensionCollection = new DimensionCollection([], 0);
        $dimensionClient->method('getAll')->with(0,9)->willReturn($dimensionCollection);
        $dimensionController = $this->getMockBuilder(DimensionController::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$dimensionClient, $characterClient])
            ->onlyMethods(['render'])
            ->getMock();

        $dimensionController->method('render')
            ->with('dimension/index.html.twig', $this->anything())
            ->willReturnCallback(function($template, $params) use ($dimensionCollection){
                $this->assertSame($dimensionCollection, $params['dimensions']);
                $this->assertSame(1, $params['page']);
                $this->assertSame(9, $params['itemsPerPage']);
                return new Response();
            });
        $dimensionController->dimensionIndex($this->getMockBuilder(Request::class)->getMock());
    }

    public function testDimensionShow()
    {
        $dimensionClient = $this->getMockBuilder(DimensionClient::class)->disableOriginalConstructor()->getMock();
        $characterClient = $this->getMockBuilder(CharacterClient::class)->disableOriginalConstructor()->getMock();

        $dimension = new Dimension(1383588489, "");
        $dimensionClient->method('get')->with(1383588489)->willReturn($dimension);

        $characterCollection = new CharacterCollection([], 0);
        $characterClient->method('getBulk')->willReturn($characterCollection);

        $dimensionController = $this->getMockBuilder(DimensionController::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$dimensionClient, $characterClient])
            ->onlyMethods(['render'])
            ->getMock();

        $dimensionController->method('render')
            ->with('dimension/show.html.twig', $this->anything())
            ->willReturnCallback(function($template, $params) use ($dimension, $characterCollection){
                $this->assertSame($dimension, $params['dimension']);
                $this->assertSame($characterCollection, $params['residents']);
                return new Response();
            });

        $dimensionController->dimensionShow(1383588489);
    }
}
