<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Antispam extends Constraint
{
    public string $message = 'This value contains disallowed words.';

    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
