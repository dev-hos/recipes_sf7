<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BanWordValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var BanWord $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $value = strtolower($value);

        foreach ($constraint->banWords as $banword) {
            if (str_contains($value, $banword)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ banword }}', $banword)
                    ->addViolation();
            }
        }

        // TODO: implement the validation here

    }
}
