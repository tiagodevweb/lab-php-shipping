<?php

namespace Tdw\Shipping\Infra\Service;


use PHPUnit\Framework\TestCase;
use Slim\Views\Twig;
use Tdw\Shipping\Domain\Service\View;

class TwigViewTest extends TestCase
{
    public function test_instance_of()
    {
        $view = new TwigView($this->createMock(Twig::class));
        $this->assertInstanceOf( View::class, $view );
    }
}