<?php declare(strict_types=1);

namespace AlanVdb\App\Validator;

use InvalidArgumentException;

abstract class AbstractValidator implements ValidatorInterface
{
    protected $errors = [];

    public function getErrors() : array
    {
        return $this->errors;
    }

    abstract public function validate(string $value) : bool;
}
