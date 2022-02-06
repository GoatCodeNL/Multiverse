<?php

namespace App\Tests\Controller;

use App\Controller\EpisodeController;
use App\RickMortyClient\Character\CharacterClient;
use App\RickMortyClient\Character\CharacterCollection;
use App\RickMortyClient\Episode\Episode;
use App\RickMortyClient\Episode\EpisodeClient;
use App\RickMortyClient\Episode\EpisodeCollection;
use Monolog\Test\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EpisodeControllerTest extends TestCase
{
    public function testEpisodeIndex()
    {
        $episodeClient = $this->getMockBuilder(EpisodeClient::class)->disableOriginalConstructor()->getMock();
        $characterClient = $this->getMockBuilder(CharacterClient::class)->disableOriginalConstructor()->getMock();

        $episodeCollection = new EpisodeCollection([], 0);
        $episodeClient->method('getAll')->with(0,8)->willReturn($episodeCollection);
        $episodeController = $this->getMockBuilder(EpisodeController::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$episodeClient, $characterClient])
            ->onlyMethods(['render'])
            ->getMock();

        $episodeController->method('render')
            ->with('episode/index.html.twig', $this->anything())
            ->willReturnCallback(function($template, $params) use ($episodeCollection){
                $this->assertSame($episodeCollection, $params['episodes']);
                $this->assertSame(1, $params['page']);
                $this->assertSame(8, $params['itemsPerPage']);
                return new Response();
            });
        $episodeController->episodeIndex($this->getMockBuilder(Request::class)->getMock());
    }

    public function testEpisodeShow()
    {
        $episodeClient = $this->getMockBuilder(EpisodeClient::class)->disableOriginalConstructor()->getMock();
        $characterClient = $this->getMockBuilder(CharacterClient::class)->disableOriginalConstructor()->getMock();

        $episode = new Episode(2, "","","",[]);
        $episodeClient->method('get')->with(2)->willReturn($episode);

        $characterCollection = new CharacterCollection([], 0);
        $characterClient->method('getBulk')->willReturn($characterCollection);

        $episodeController = $this->getMockBuilder(EpisodeController::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$episodeClient, $characterClient])
            ->onlyMethods(['render'])
            ->getMock();

        $episodeController->method('render')
            ->with('episode/show.html.twig', $this->anything())
            ->willReturnCallback(function($template, $params) use ($episode, $characterCollection){
                $this->assertSame($episode, $params['episode']);
                $this->assertSame($characterCollection, $params['characters']);
                return new Response();
            });

        $episodeController->episodeShow(2);
    }
}
