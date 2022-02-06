<?php

namespace App\Tests\Controller;

use App\Controller\CharacterController;
use App\RickMortyClient\Character\Character;
use App\RickMortyClient\Character\CharacterClient;
use App\RickMortyClient\Character\CharacterCollection;
use App\RickMortyClient\Episode\EpisodeClient;
use App\RickMortyClient\Episode\EpisodeCollection;
use App\RickMortyClient\Location\Location;
use App\RickMortyClient\Location\LocationClient;
use Monolog\Test\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CharacterControllerTest extends TestCase
{
    public function testCharacterIndex()
    {
        $characterClient = $this->getMockBuilder(CharacterClient::class)->disableOriginalConstructor()->getMock();
        $episodeClient = $this->getMockBuilder(EpisodeClient::class)->disableOriginalConstructor()->getMock();
        $locationClient = $this->getMockBuilder(LocationClient::class)->disableOriginalConstructor()->getMock();

        $characterCollection = new CharacterCollection([], 0);
        $characterClient->method('getAll')->with(0,8)->willReturn($characterCollection);
        $characterController = $this->getMockBuilder(CharacterController::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$characterClient, $locationClient, $episodeClient])
            ->onlyMethods(['render'])
            ->getMock();

        $characterController->method('render')
            ->with('character/index.html.twig', $this->anything())
            ->willReturnCallback(function($template, $params) use ($characterCollection){
                $this->assertSame($characterCollection, $params['characters']);
                $this->assertSame(1, $params['page']);
                $this->assertSame(8, $params['itemsPerPage']);
                return new Response();
            });
        $characterController->characterIndex($this->getMockBuilder(Request::class)->getMock());
    }

    public function testCharacterIndexPage2()
    {
        $characterClient = $this->getMockBuilder(CharacterClient::class)->disableOriginalConstructor()->getMock();
        $episodeClient = $this->getMockBuilder(EpisodeClient::class)->disableOriginalConstructor()->getMock();
        $locationClient = $this->getMockBuilder(LocationClient::class)->disableOriginalConstructor()->getMock();

        $characterCollection = new CharacterCollection([], 0);
        $characterClient->method('getAll')->with(8,8)->willReturn($characterCollection);
        $characterController = $this->getMockBuilder(CharacterController::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$characterClient, $locationClient, $episodeClient])
            ->onlyMethods(['render'])
            ->getMock();

        $characterController->method('render')
            ->with('character/index.html.twig', $this->anything())
            ->willReturnCallback(function($template, $params) use ($characterCollection){
                $this->assertSame($characterCollection, $params['characters']);
                $this->assertEquals(2, $params['page']);
                $this->assertSame(8, $params['itemsPerPage']);
                return new Response();
            });
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->method('get')->with('page')->willReturn("2");
        $characterController->characterIndex($request);
    }

    public function testCharacterShow()
    {
        $characterClient = $this->getMockBuilder(CharacterClient::class)->disableOriginalConstructor()->getMock();
        $episodeClient = $this->getMockBuilder(EpisodeClient::class)->disableOriginalConstructor()->getMock();
        $locationClient = $this->getMockBuilder(LocationClient::class)->disableOriginalConstructor()->getMock();

        $character = new Character(16, "test", "", "", "", "", (object)[], 1, (object)[], 2, "", []);
        $characterClient->method('get')->with(16)->willReturn($character);

        $origin = new Location(1, "", "", "", []);
        $location = new Location(2, "", "", "", []);
        $locationClient->method('get')->withConsecutive([1],[2])->willReturnOnConsecutiveCalls($origin, $location);

        $episodeCollection = new EpisodeCollection([], 0);
        $episodeClient->method('getBulk')->willReturn($episodeCollection);

        $characterController = $this->getMockBuilder(CharacterController::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$characterClient, $locationClient, $episodeClient])
            ->onlyMethods(['render'])
            ->getMock();

        $characterController->method('render')
            ->with('character/show.html.twig', $this->anything())
            ->willReturnCallback(function($template, $params) use ($character, $origin, $location, $episodeCollection){
                $this->assertSame($character, $params['character']);
                $this->assertSame($origin, $params['origin']);
                $this->assertSame($location, $params['location']);
                $this->assertSame($episodeCollection, $params['episodes']);
                return new Response();
            });
        $characterController->characterShow(16);
    }
}
