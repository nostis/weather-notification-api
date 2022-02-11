<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class StrongPassword extends Constraint
{
    public string $message = 'Password must contains at least one small letter, at least one big letter and at least one digit!';
}