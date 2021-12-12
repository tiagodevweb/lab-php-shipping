<?php

namespace Tdw\Shipping\App\Action;


use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tdw\Shipping\Infra\Service\Validation\Rule\Digit;
use Tdw\Shipping\Infra\Service\Validation\Rule\Exact;
use Tdw\Shipping\Infra\Service\Validation\Rule\Regex;
use Tdw\Shipping\Infra\Service\Validation\Validator;

class CarriersRangeSearch
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

    /**
     * @var \Tdw\Shipping\Infra\Persistence\CarriersRangeSearch
     */
    private $persistenceCarriersRangeSearch;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->view = $container->get('view');
        $this->flash = $container->get('flash');
        $this->persistenceCarriersRangeSearch = $this->container->get('persistenceCarriersRangeSearch');
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        if ( sizeof( $data = $request->getQueryParams()) and $data['postCode'] and $data['weight'] ) {
            $postCode = filter_var($data['postCode'], FILTER_DEFAULT);
            $weight = filter_var($data['weight'], FILTER_DEFAULT);

            $validator = new Validator();
            $validator->add(new Digit('CEP', $postCode))
                      ->add(new Exact('CEP', $postCode, 8))
                      ->add(new Regex('PESO', $weight, '[0-9]*.?[0-9]{1,3}', ' Ex.: 1|1.2|1.23|1.234'));
            if ( $validator->fails() ) {
                $this->flash->addMessage('error',$validator->messages());
                return $response->withStatus(302)->withHeader('Location', '/');
            }

            $collection = $this->persistenceCarriersRangeSearch->execute($postCode,$weight);
            return $this->view->render($response, 'pages/carrierRangeSearch/index.twig',[
                'carriersRangeSearch' => $collection->all()
            ]);
        }
        $this->flash->addMessage('error','Informe CEP e PESO.');
        return $response->withStatus(302)->withHeader('Location', '/');
    }

}