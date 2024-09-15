<?php declare(strict_types=1);

namespace AlanVdb\App\Configuration\Exception;

use AlanVdb\App\Configuration\Definition\ConfigurationExceptionInterface;
use InvalidArgumentException;

class InvalidConfigurationProvided
    extends InvalidArgumentException
    implements ConfigurationExceptionInterface
{}
