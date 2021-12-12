<?php

declare(strict_types=1);

namespace Tdw\Shipping\Infra\Service;

use Psr\Http\Message\ResponseInterface;
use Tdw\Shipping\Domain\Service\View as IView;
use Slim\Views\Twig;

class TwigView implements IView
{
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function render(
        ResponseInterface $response, string $file, array $vars = []
    )
    {
        return $this->twig->render( $response, $file, $vars );
    }
}