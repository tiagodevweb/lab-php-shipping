<?php

namespace Tdw\Shipping\App\Action;


use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tdw\Shipping\Infra\Service\Validation\Rule\Digit;
use Tdw\Shipping\Infra\Service\Validation\Rule\Exact;
use Tdw\Shipping\Infra\Service\Validation\Rule\Regex;
use Tdw\Shipping\Infra\Service\Validation\Validator;

class CreateCarriersRange
{
    private $container;

    /**
     * @var \Tdw\Shipping\Domain\Persistence\CarriersRange
     */
    private $carriersRange;

    /**
     * @var \Tdw\Shipping\Domain\Service\FlashMessage $flash
     */
    private $flash;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->flash = $container->get('flash');
        $this->carriersRange = $this->container->get('persistenceCarriersRange');
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        $data = $request->getParsedBody();
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
            return $response->withStatus(302)->withHeader('Location', '/regioes');
        }
        try {
            $this->carriersRange->create(
                (int)$data['initialPostCode'], (int)$data['finalPostCode'],
                (float)$data['minWeight'], ('' == $data['maxWeight']) ? null : $data['maxWeight'] ,
                (float)$data['price'], $data['carrierId']
            );
        } catch ( \Exception $e ) {
            $this->flash->addMessage('error',$e->getMessage());
            return $response->withStatus(302)->withHeader('Location', '/regioes');
        }
        $this->flash->addMessage('success','Região cadastrada com sucesso.');
        return $response->withStatus(302)->withHeader('Location', '/regioes');
    }

}