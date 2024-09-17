<?php

namespace App;

use AlanVdb\Router\Definition\RequestMatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use AlanVdb\Router\Exception\RouteNotFound;
use AlanVdb\Router\Exception\MethodNotAllowed;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'bootstrap.php';

// ROUTING

try {
    $route = $container->get(RequestMatcherInterface::class)->matchRequest($container->get(ServerRequestInterface::class));
} catch(RouteNotFound $e) {
    die('404 Error');
} catch(MethodNotAllowed $e) {
    die('405 Error');
}


// RESPONSE
$response = call_user_func($route->getTarget(), $route->getParams());

http_response_code($response->getStatusCode());

foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
$body = $response->getBody();

//var_dump((string) $body);

while (!$body->eof()) {
    echo $body->read(1024);
}
