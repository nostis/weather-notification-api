<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class StrongPasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof StrongPassword) {
            throw new UnexpectedTypeException($constraint, StrongPassword::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if($this->isPasswordNotStrongEnough($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

    private function isPasswordNotStrongEnough(string $password): bool
    {
        return
            $this->isPasswordDoNotContainSmallLetter($password) ||
            $this->isPasswordDoNotContainBigLetter($password)   ||
            $this->isPasswordDoNotContainDigit($password)
            ;
    }

    private function isPasswordDoNotContainSmallLetter(string $password): bool
    {
        return !preg_match('/(?=.*[a-z])/', $password);
    }

    private function isPasswordDoNotContainBigLetter(string $password): bool
    {
        return !preg_match('/(?=.*[A-Z])/', $password);
    }

    private function isPasswordDoNotContainDigit(string $password): bool
    {
        return !preg_match('/(?=.*\d)/', $password);
    }
}