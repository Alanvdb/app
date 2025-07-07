<?php

namespace AlanVdb\Server\Exception;

use AlanVdb\Server\Definition\ServerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

class ServerConfigurationNotFound
    extends RuntimeException
    implements ServerExceptionInterface, NotFoundExceptionInterface
{}
