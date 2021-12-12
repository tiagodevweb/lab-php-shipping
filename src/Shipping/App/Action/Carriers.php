<?php

namespace Tdw\Shipping\App\Action;


use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Carriers
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
        return $this->view->render( $response, 'pages/carrier/index.twig',[
            'carriers' => $this->carriers(),
            'success' => $this->flash->hasMessage('success') ? $this->flash->getMessage('success') : null,
            'error' => $this->flash->hasMessage('error') ? $this->flash->getMessage('error') : null
        ]);
    }

    private function carriers()
    {
        /**@var \Tdw\Shipping\Domain\Persistence\Carriers $carriers*/
        $carriers = $this->container->get('persistenceCarriers');
        return $carriers->collection()->all();
    }

}