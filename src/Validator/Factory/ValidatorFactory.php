<?php declare(strict_types=1);

namespace AlanVdb\App\Validator\Factory;

use AlanVdb\App\Validator\Definition\ValidatorFactoryInterface;
use AlanVdb\App\Validator\Definition\ValidatorInterface;

use AlanVdb\App\Validator\StringLengthValidator;
use AlanVdb\App\Validator\EmailValidator;

class ValidatorFactory implements ValidatorFactoryInterface
{
    public function createStringLengthValidator(int $min, int $max, string $errorMessage = 'must be {min} to {max} characters long') : ValidatorInterface
    {
        return new StringLengthValidator($min, $max, $errorMessage);
    }

    public function createEmailValidator(string $errorMessage = 'must be a valid email address') : ValidatorInterface
    {
        return new EmailValidator($errorMessage);
    }

    public function createRegexValidator(string $pattern, string $errorMessage) : ValidatorInterface
    {
        return new RegexValidator($pattern, $errorMessage);
    }
}
