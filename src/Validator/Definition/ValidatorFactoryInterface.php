<?php declare(strict_types=1);

namespace AlanVdb\App\Validator\Definition;

interface ValidatorFactoryInterface
{
    public function createStringLengthValidator(int $min, int $max) : ValidatorInterface;
}
