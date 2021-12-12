<?php

declare(strict_types=1);

namespace Tdw\Shipping\Infra\Service;


use Slim\Flash\Messages;
use Tdw\Shipping\Domain\Service\FlashMessage;

class SlimFlashMessage implements FlashMessage
{
    private $messages;

    public function __construct(Messages $messages)
    {
        $this->messages = $messages;
    }

    public function addMessage(string $key, string $value)
    {
        $this->messages->addMessage( $key, $value );
    }

    public function hasMessage(string $key): bool
    {
        return $this->messages->hasMessage( $key );
    }

    public function getMessage(string $key)
    {
        return $this->messages->getMessage( $key )[0];
    }

    public function getMessages(): array
    {
        return $this->messages->getMessages();
    }
}