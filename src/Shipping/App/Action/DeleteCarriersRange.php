<?php

namespace Tdw\Shipping\App\Action;


use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tdw\Shipping\Infra\Persistence\CarriersRange;

class DeleteCarriersRange
{
    private $container;

    /**
     * @var \Tdw\Shipping\Domain\Service\FlashMessage $flash
     */
    private $flash;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->flash = $container->get('flash');
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        $id = filter_var($args['id'], FILTER_DEFAULT);
        $carriersRange = new CarriersRange( $this->container->get('pdo'), (int)$id );
        try {
            $carriersRange->remove( $id );
        } catch ( \Exception $e ) {
            $this->flash->addMessage('error', $e->getMessage());
            return $response->withStatus(302)->withHeader('Location', '/regioes');
        }
        $this->flash->addMessage('success','Região excluida com sucesso.');
        return $response->withStatus(301)->withHeader('Location', '/regioes');
    }

}