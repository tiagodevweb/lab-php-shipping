<?php

namespace Tdw\Shipping\App\Action;


use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CarriersRange
{
    private $container;

    /**
     * @var \Tdw\Shipping\Domain\Service\View
     */
    private $view;

    /**
     * @var \Tdw\Shipping\Domain\Service\FlashMessage $flash
     */
    private $flash;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->view = $container->get('view');
        $this->flash = $container->get('flash');
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        return $this->view->render( $response, 'pages/carrierRange/index.twig',[
            'carriersRange' => $this->carriersRange(),
            'carriers' => $this->carriers(),
            'success' => $this->flash->hasMessage('success') ? $this->flash->getMessage('success') : null,
            'error' => $this->flash->hasMessage('error') ? $this->flash->getMessage('error') : null
        ]);
    }

    private function carriersRange()
    {
        /**@var \Tdw\Shipping\Domain\Persistence\CarriersRange $carriersRange*/
        $carriersRange = $this->container->get('persistenceCarriersRange');
        return $carriersRange->collection()->all();
    }

    private function carriers()
    {
        /**@var \Tdw\Shipping\Domain\Persistence\Carriers $carriers*/
        $carriers = $this->container->get('persistenceCarriers');
        return $carriers->collection()->all();
    }

}