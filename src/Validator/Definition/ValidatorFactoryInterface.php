<?php declare(strict_types=1);

namespace AlanVdb\App\Validator\Definition;

interface ValidatorFactoryInterface
{
    public function createStringLengthValidator(int $min, int $max, string $errorMessage) : ValidatorInterface;

    public function createEmailValidator(string $errorMessage) : ValidatorInterface;

    public function createRegexValidator(string $pattern, string $errorMessage) : ValidatorInterface;
}
