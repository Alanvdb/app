<?php declare(strict_types=1);

namespace AlanVdb\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;

use AlanVdb\Controller\AbstractController;
use RuntimeException;

class RouteTargetResolverMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected ContainerInterface $container
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getAttribute('matchedRoute', false) === false) {
            throw new RuntimeException("Missing request attribute : 'matchedRoute'.");
        }
        $route = $request->getAttribute('matchedRoute');
        list($controllerClass, $method) = $route->getTarget();

        if (!is_subclass_of($controllerClass, AbstractController::class)) {
            throw new RuntimeException(sprintf(
                "Provided controller class does not extend %s : '%s'.",
                AbstractController::class,
                $controllerClass
            ));
        }
        $controller = new $controllerClass($this->container);

        if (!method_exists($controller, $method)) {
            throw new RuntimeException(sprintf("Method does not exists %s::%s().", get_class($controller), $method));
        }

        return $controller->$method($route->getParams());
    }
}
