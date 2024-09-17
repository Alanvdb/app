<?php declare(strict_types=1);

namespace AlanVdb\Model\Validator\Definition;

interface ValidatorInterface
{
    public function validate(string $value) : bool;

    public function getErrors() : array;
}
