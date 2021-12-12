<?php

declare(strict_types=1);

namespace Tdw\Shipping\Domain\Service;


use Psr\Http\Message\ResponseInterface;

interface View
{
    public function render(
        ResponseInterface $response, string $file, array $vars = []
    );
}