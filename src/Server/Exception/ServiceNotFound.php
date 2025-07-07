<?php

namespace AlanVdb\Server\Exception;

use AlanVdb\Server\Definition\ServerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

class ServiceNotFound
    extends RuntimeException
    implements ServerExceptionInterface, NotFoundExceptionInterface
{}
