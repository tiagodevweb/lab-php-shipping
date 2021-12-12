<?php

declare(strict_types=1);

namespace Tdw\Shipping\Domain\Service;


interface FlashMessage
{
    public function addMessage(string $key, string $value);
    public function hasMessage(string $key): bool;
    public function getMessage(string $key);
    public function getMessages(): array;
}