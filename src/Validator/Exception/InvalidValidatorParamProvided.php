<?php declare(strict_types=1);

namespace AlanVdb\App\Validator\Exception;

use AlanVdb\App\Validator\Definition\ValidatorExceptionInterface;
use InvalidArgumentException;

class InvalidValidatorParamProvided 
    extends InvalidArgumentException
    implements ValidatorExceptionInterface
{}
