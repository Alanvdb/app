<?php declare(strict_types=1);

namespace AlanVdb\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment as TwigEnvironment;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

use AlanVdb\Controller\AbstractController;
use RuntimeException;

class ModuleLoaderMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected ResponseFactoryInterface $responseFactory,
        protected StreamFactoryInterface   $streamFactory,
        protected EntityManagerInterface   $entityManager,
        protected TwigEnvironment          $twig
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getAttribute('matchedRoute', false) === false) {
            throw new RuntimeException(sprintf("Missing request attribute : 'matchedRoute'."));
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
        $controller = new $controllerClass(
            $request,
            $this->responseFactory,
            $this->streamFactory,
            $this->entityManager,
            $this->twig
        );

        if (!method_exists($controller, $method)) {
            throw new RuntimeException(sprintf("Method does not exists %s::%s().", $controller, $method));
        }

        return $controller->$method($route->getParams());
    }
}
