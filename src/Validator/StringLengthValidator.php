<?php declare(strict_types=1);

namespace AlanVdb\App\Validator;

use AlanVdb\App\Validator\Definition\ValidatorInterface;
use AlanVdb\App\Validator\Exception\InvalidValidatorParamProvided;

class StringLengthValidator extends AbstractValidator implements ValidatorInterface
{
    protected int $min;
    protected int $max;
    
    public function __construct(int $min, int $max)
    {
        if ($min < 0) {
            throw new InvalidValidatorParamProvided('Minimum cannot be lower than 0.');
        } elseif ($max < $min) {
            throw new InvalidValidatorParamProvided('Maximum cannot be lower than minimum.');
        }
        $this->min = $min;
        $this->max = $max;
    }

    public function validate(string $value) : bool
    {
        $length = strlen($value);

        if ($length < $this->min || $length > $this->max) {
            $this->errors[] = "must be {$this->min} to {$this->max} characters long";
            return false;
        }
        return true;
    }
}
