<?php

namespace Tdw\Shipping\App\Action;


use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tdw\Shipping\Infra\Persistence\Carrier;
use Tdw\Shipping\Infra\Service\Validation\Rule\Between;
use Tdw\Shipping\Infra\Service\Validation\Rule\Digit;
use Tdw\Shipping\Infra\Service\Validation\Rule\Required;
use Tdw\Shipping\Infra\Service\Validation\Validator;

class UpdateCarriers
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
        $carrier = new Carrier( $this->container->get('pdo'), (int)$id );
        $data = $carrier->data();
        return $this->view->render( $response, 'pages/carrier/edit.twig',[
            'id' => $data->id(),
            'name' => $data->name(),
            'active' => $data->active()
        ]);
    }

    public function post(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        $data = $request->getParsedBody();
        $carrier = new Carrier( $this->container->get('pdo'), (int)$data['id'] );
        $validator = new Validator();
        $validator->add(new Required('NOME', $data['name']))
                  ->add(new Between('NOME', $data['name'], 3, 25))
                  ->add(new Digit('ATIVO', $data['active']));
        if ( $validator->fails() ) {
            $this->flash->addMessage('error',$validator->messages());
            return $response->withStatus(302)->withHeader('Location', '/transportadoras');
        }
        try {
            $carrier->update($data['name'], (bool)$data['active']);
        } catch ( \Exception $e ) {
            $this->flash->addMessage('error', $e->getMessage());
            return $response->withStatus(302)->withHeader('Location', '/transportadoras');
        }
        $this->flash->addMessage('success','Transportadora atualizada com sucesso.');
        return $response->withStatus(301)->withHeader('Location', '/transportadoras');
    }

}