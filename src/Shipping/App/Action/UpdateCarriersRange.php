<?php

namespace Tdw\Shipping\App\Action;


use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tdw\Shipping\Infra\Persistence\CarrierRange;
use Tdw\Shipping\Infra\Service\Validation\Rule\Digit;
use Tdw\Shipping\Infra\Service\Validation\Rule\Exact;
use Tdw\Shipping\Infra\Service\Validation\Rule\Regex;
use Tdw\Shipping\Infra\Service\Validation\Validator;

class UpdateCarriersRange
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
        $id = filter_var($args['id'], FILTER_DEFAULT);
        $carrierRange = new CarrierRange( $this->container->get('pdo'), (int)$id );
        $data = $carrierRange->data();
        return $this->view->render( $response, 'pages/carrierRange/edit.twig',[
            'id' => $data->id(),
            'initialPostCode' => $data->initialPostCode(),
            'finalPostCode' => $data->finalPostCode(),
            'minWeight' => $data->minWeight(),
            'maxWeight' => $data->maxWeight(),
            'price' => $data->price(),
            'carrierId' => $data->carrierId(),
            'carrierName' => $data->carrierName(),
            'carriers' => $this->carriers(),
            'success' => $this->flash->hasMessage('success') ? $this->flash->getMessage('success') : null,
            'error' => $this->flash->hasMessage('error') ? $this->flash->getMessage('error') : null
        ]);
    }

    public function post(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        $data = $request->getParsedBody();
        $carrierRange = new CarrierRange( $this->container->get('pdo'), (int)$data['id'] );
        $validator = new Validator();
        $validator->add(new Digit('CEP INICIAL', $data['initialPostCode']))
                  ->add(new Exact('CEP INICIAL', $data['initialPostCode'], 8))
                  ->add(new Digit('CEP FINAL', $data['initialPostCode']))
                  ->add(new Exact('CEP FINAL', $data['initialPostCode'], 8))
                  ->add(new Regex('PESO MÍNIMO', $data['minWeight'], '[0-9]*.?[0-9]{1,3}', ' Ex.: 1|1.2|1.23|1.234'));
        if ( '' != $data['maxWeight'] ) {
            $validator->add(new Regex('PESO MÁXIMO', $data['maxWeight'], '[0-9]*.?[0-9]{1,3}', ' Ex.: 1|1.2|1.23|1.234'));
        }
        $validator->add(new Regex('PREÇO', $data['price'], '[0-9]*.?[0-9]{1,2}', ' Ex.: 50|50.2|50.98'))
            ->add(new Digit('TRANSPORTADORA', $data['carrierId']));

        if ( $validator->fails() ) {
            $this->flash->addMessage('error',$validator->messages());
            return $response->withStatus(302)->withHeader('Location', '/regioes/editar/'.$data['id']);
        }
        try {
            $carrierRange->update(
                $data['initialPostCode'], $data['finalPostCode'], $data['minWeight'], $data['maxWeight'],
                $data['price'], $data['carrierId']
            );
        } catch ( \Exception $e ) {
            $this->flash->addMessage('error', $e->getMessage());
            return $response->withStatus(302)->withHeader('Location', '/regioes');
        }
        $this->flash->addMessage('success','Região atualizada com sucesso.');
        return $response->withStatus(301)->withHeader('Location', '/regioes');
    }

    private function carriers()
    {
        /**@var \Tdw\Shipping\Domain\Persistence\Carriers $carriers*/
        $carriers = $this->container->get('persistenceCarriers');
        return $carriers->collection()->all();
    }

}