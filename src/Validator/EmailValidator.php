<?php declare(strict_types=1);

namespace AlanVdb\App\Validator;

use AlanVdb\App\Validator\Definition\ValidatorInterface;

class EmailValidator extends AbstractValidator implements ValidatorInterface
{
    public function __construct(string $errorMessage = 'must be a valid email address')
    {
        $this->errorMessage = $errorMessage;
    }

    public function validate(string $value) : bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
