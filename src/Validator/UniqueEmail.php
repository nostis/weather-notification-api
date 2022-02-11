<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueEmail extends Constraint
{
    public string $message = 'The user with email "{{ string }}" already exists!';
}