<?php declare(strict_types=1);

namespace AlanVdb\Model\Validator\Exception;

use AlanVdb\Model\Validator\Definition\ValidatorExceptionInterface;
use InvalidArgumentException;

class InvalidValidatorParamProvided 
    extends InvalidArgumentException
    implements ValidatorExceptionInterface
{}
