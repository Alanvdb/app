<?php declare(strict_types=1);

namespace AlanVdb\Model\Validator;

use InvalidArgumentException;

abstract class AbstractValidator implements ValidatorInterface
{
    protected string $errorMessage;

    public function getErrorMessage() : string
    {
        return $this->error;
    }

    abstract public function validate(string $value) : bool;
}
