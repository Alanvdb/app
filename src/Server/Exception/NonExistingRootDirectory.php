<?php

namespace AlanVdb\Server\Exception;

use AlanVdb\Server\Definition\ServerExceptionInterface;
use InvalidArgumentException;

class NonExistingRootDirectory
    extends InvalidArgumentException
    implements ServerExceptionInterface
{}
