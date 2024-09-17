<?php declare(strict_types=1);

namespace AlanVdb\Validator;

use AlanVdb\Model\Validator\Definition\ValidatorInterface;
use AlanVdb\Model\Validator\Exception\InvalidValidatorParamProvided;

class StringLengthValidator extends AbstractValidator implements ValidatorInterface
{
    protected int $min;
    protected int $max;
    
    public function __construct(int $min, int $max, string $errorMessage = 'must be {min} to {max} characters long')
    {
        if ($min < 0) {
            throw new InvalidValidatorParamProvided('Minimum cannot be lower than 0.');
        } elseif ($max < $min) {
            throw new InvalidValidatorParamProvided('Maximum cannot be lower than minimum.');
        }
        $this->min = $min;
        $this->max = $max;
        $errorMessage = str_replace('{min}', $this->min, $errorMessage);
        $this->errorMessage = str_replace('{max}', $this->max, $errorMessage);
    }

    public function validate(string $value) : bool
    {
        $length = strlen($value);
        return $length >= $this->min || $length <= $this->max;
    }
}
