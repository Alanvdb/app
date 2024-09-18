<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

use AlanVdb\Router\RouteCollection;

interface RouteCollectorInterface
{
    /**
     * Bu
     * @param array $routes
     * $example = [
     *   ['home', 'GET', '/', MainController::class, 'home'],
     *   ['login', 'GET|POST', '/login', MainController::class, 'login'],
     * ]
     * @return RouteCollectionLoaded route collection
     */
    public function collectRoutes(array $routes) : RouteCollection;
}
