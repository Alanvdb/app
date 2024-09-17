<?php declare(strict_types=1);

namespace AlanVdb\Router;

use AlanVdb\Router\Definition\RouteCollectorInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use AlanVdb\Router\Definition\UriGeneratorInterface;
use AlanVdb\Router\Definition\RouteCollectionInterface;
use Doctrine\ORM\EntityManagerInterface;

use AlanVdb\Router\Factory\RouterFactory;
use Guzzle\Psr7\HttpFactory;

use AlanVdb\Router\Exception\InvalidRouteConfiguration;

class RouteCollector implements RouteCollectorInterface
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $routes
     * $example = [
     *   ['home', 'GET', '/', MainController::class, 'home'],
     *   ['login', 'GET|POST', '/login', MainController::class, 'login'],
     * ]
     */
    public function collectRoutes(array $routeParams) : RouteCollectionInterface
    {
        $routeCollection = (new RouterFactory())->createRouteCollection();

        foreach ($routeParams as $route) {

            if (!is_array($route)) {
                throw new InvalidRouteConfiguration("Routes configuration file must return an array of Route params.");
            }
    
            $routeCollection->add($route[0], function() use($route)
            {
                return (new RouterFactory())->createRoute($route[0], $route[1], $route[2], function($args) use($route) {
                    $controller = new $route[3](
                        $this->request,
                        $this->responseFactory,
                        $this->streamFactory,
                        $this->uriGenerator,
                        $this->entityManager
                    );
                    $method = $route[4];
                    return $controller->$method($args);
                });
            });
        }
        return $routeCollection;
    }
}
