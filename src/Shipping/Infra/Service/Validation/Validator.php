<?php

declare( strict_types=1 );

namespace Tdw\Shipping\Infra\Service\Validation;

use Tdw\Shipping\Domain\Service\Validation\Rule;
use Tdw\Shipping\Domain\Service\Validation\Validator as IValidator;

class Validator implements IValidator
{
    private $rules = [];
    private $errors = [];

    public function add(Rule $rule): IValidator
    {
        $this->rules[] = $rule;
        return $this;
    }

    public function fails(): bool
    {
        if ( sizeof( $this->rules ) ) {
            /**@var Rule $rule*/
            foreach ( $this->rules as $rule ) {
                if ( $rule->fail() ) {
                    $this->errors[] = $rule->message();
                }
            }
        }
        return count( $this->errors ) > 0;
    }

    public function messages(): string
    {
        return implode( PHP_EOL, $this->errors );
    }
}