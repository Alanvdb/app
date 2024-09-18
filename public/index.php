<?php

namespace App;

use AlanVdb\Router\Definition\RequestMatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use AlanVdb\Router\RouteCollection;
use AlanVdb\Router\Exception\RouteNotFound;
use AlanVdb\Router\Exception\MethodNotAllowed;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'bootstrap.php';
