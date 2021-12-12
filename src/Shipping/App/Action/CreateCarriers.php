<?php

namespace Tdw\Shipping\App\Action;


use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tdw\Shipping\Infra\Service\Validation\Rule\Alpha;
use Tdw\Shipping\Infra\Service\Validation\Rule\Between;
use Tdw\Shipping\Infra\Service\Validation\Rule\Digit;
use Tdw\Shipping\Infra\Service\Validation\Rule\Required;
use Tdw\Shipping\Infra\Service\Validation\Validator;

class CreateCarriers
{
    /**
     * @var \Tdw\Shipping\Domain\Persistence\Carriers $carriers
     */
    private $persistenceCarriers;

    /**
     * @var \Tdw\Shipping\Domain\Service\FlashMessage $flash
     */
    private $flash;

    public function __construct(ContainerInterface $container)
    {
        $this->persistenceCarriers = $container->get('persistenceCarriers');
        $this->flash = $container->get('flash');
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        $data = $request->getParsedBody();
        $validator = new Validator();
        $validator->add(new Required('NOME', $data['name']))
                  ->add(new Between('NOME', $data['name'], 3, 25))
                  ->add(new Digit('ATIVO', $data['active']));
        if ( $validator->fails() ) {
            $this->flash->addMessage('error',$validator->messages());
            return $response->withStatus(302)->withHeader('Location', '/transportadoras');
        }
        try {
            $this->persistenceCarriers->create($data['name'],(bool)$data['active']);
        } catch ( \Exception $e ) {
            $this->flash->addMessage('error', $e->getMessage());
            return $response->withStatus(302)->withHeader('Location', '/transportadoras');
        }
        $this->flash->addMessage('success','Transportadora cadastrada com sucesso.');
        return $response->withStatus(302)->withHeader('Location', '/transportadoras');
    }

}