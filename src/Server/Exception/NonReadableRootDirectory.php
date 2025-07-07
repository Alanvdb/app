<?php

namespace AlanVdb\Server\Exception;

use AlanVdb\Server\Definition\ServerExceptionInterface;
use RuntimeException;

class NonReadableRootDirectory
    extends RuntimeException
    implements ServerExceptionInterface
{}
