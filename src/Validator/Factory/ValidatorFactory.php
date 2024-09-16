<?php declare(strict_types=1);

namespace AlanVdb\App\Validator\Factory;

use AlanVdb\App\Validator\Definition\ValidatorFactoryInterface;
use AlanVdb\App\Validator\Definition\ValidatorInterface;

use AlanVdb\App\Validator\StringLengthValidator;

class ValidatorFactory implements ValidatorFactoryInterface
{
    public function createStringLengthValidator(int $min, int $max) : ValidatorInterface
    {
        return new StringLengthValidator($min, $max);
    }
}
