<?php

declare(strict_types=1);

namespace Tdw\Shipping\Domain\Service\Validation;

interface Validator
{
    public function add(Rule $rule): Validator;
    public function fails(): bool;
    public function messages(): string;
}