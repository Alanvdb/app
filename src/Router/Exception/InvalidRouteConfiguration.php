<?php declare(strict_types=1);

namespace AlanVdb\Router\Exception;

use AlanVdb\Router\Definition\RouterExceptionInterface;
use RuntimeException;

class InvalidRouteConfiguration
    extends RuntimeException
    implements RouterExceptionInterface
{}
