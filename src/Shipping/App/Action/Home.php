<?php

namespace Tdw\Shipping\App\Action;


use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Home
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        /**@var \Tdw\Shipping\Domain\Service\View $view*/
        $view = $this->container->get('view');
        /**@var \Tdw\Shipping\Domain\Service\FlashMessage $flash*/
        $flash = $this->container->get('flash');
        return $view->render( $response, 'pages/home/index.twig',[
            'success' => $flash->hasMessage('success') ? $flash->getMessage('success') : null,
            'error' => $flash->hasMessage('error') ? $flash->getMessage('error') : null
        ]);
    }

}