<?php

namespace App\Tests\Controller;

use App\Controller\PlumbusController;
use Monolog\Test\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PlumbusControllerTest extends TestCase
{
    public function testIndex()
    {
        $plumbusController = $this->getMockBuilder(PlumbusController::class)
            ->onlyMethods(['render'])
            ->getMock();

        $plumbusController->method('render')
            ->willReturnCallback(function($template, $params){
                $this->assertSame("plumbus/index.html.twig", $template);
                return new Response();
            });
        $plumbusController->index();
    }
}
