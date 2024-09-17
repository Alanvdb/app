<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

interface RouteCollectorInterface
{
    /**
     * Bu
     * @param array $routes
     * $example = [
     *   ['home', 'GET', '/', MainController::class, 'home'],
     *   ['login', 'GET|POST', '/login', MainController::class, 'login'],
     * ]
     * @return RouteCollectionInterface Loaded route collection
     */
    public function collectRoutes(array $routes) : RouteCollectionInterface;
}
