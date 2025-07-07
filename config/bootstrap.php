<?php declare(strict_types=1);

namespace App;

use AlanVdb\Server\Definition\ServerConfigurationInterface;
use AlanVdb\Server\ServerConfigurationService;
use AlanVdb\Server\Factory\VlucasDotEnvLoaderFactory as DotEnvLoaderFactory;
use AlanVdb\Server\Factory\ServerConfigurationFactory;

use AlanVdb\Container\Factory\PimplePsr11ContainerFactory as ContainerFactory;

use AlanVdb\Dispatcher\Factory\DispatcherFactory;
use AlanVdb\Middleware\RouteTargetResolverMiddleware;
use AlanVdb\Http\Factory\GuzzleServerRequestFromGlobalsFactory as ServerRequestFromGlobalsFactory;
use AlanVdb\Http\Factory\GuzzleRequestFactory as RequestFactory;
use AlanVdb\Http\Factory\GuzzleResponseFactory as ResponseFactory;
use AlanVdb\Http\Factory\GuzzleStreamFactory as StreamFactory;
use AlanVdb\Http\Factory\GuzzleUriFactory as UriFactory;
use AlanVdb\Router\Factory\RouterFactory;
use AlanVdb\Router\Factory\RoutingMiddlewareFactory;
use AlanVdb\ORM\Manager\Factory\DoctrineEntityManagerFactory as ManagerFactory;
use AlanVdb\Renderer\Factory\TwigFactory as RendererFactory;
use Doctrine\ORM\EntityManagerInterface;
use AlanVdb\Server\PhpSession as Session;


const ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..';

require ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$env = (new ServerConfigurationService(
    ROOT,
    new DotEnvLoaderFactory(),
    new ServerConfigurationFactory()
))->get(ServerConfigurationInterface::class);


// DOCTRINE ENTITY MANAGER CREATION

$dbDriver = 'pdo_' . strtolower($env->get('DB_DRIVER'));

if ($dbDriver === 'pdo_sqlite') {
    $dbParams = ['path' => '../' . $env->get('DB_PATH')];
} elseif ($dbDriver === 'pdo_mysql') {
    $dbParams = [
        'host' => $env->get('DB_HOST'),
        'dbname' => $env->get('DB_NAME'),
        'user' => $env->get('DB_USER'),
        'password' => $env->get('DB_PASSWORD')
    ];
}
$dbParams['driver'] = $dbDriver;

$entityManager = (new ManagerFactory())->createEntityManager(
    $dbParams,
    explode(',', $env->get('ENTITY_DIRECTORIES')),
    $env->get('MODEL_PROXY_DIRECTORY'),
    $env->get('MODEL_PROXY_NAMESPACE'),
    boolval($env->get('DEBUG_MODE'))
);


// TWIG ENVIRONMENT CREATION

$twig = (new RendererFactory())->createRenderer(
    '../' . $env->get('TEMPLATES_DIRECTORY'),
    filter_var($env->get('ACTIVATE_TEMPLATE_CACHE'), FILTER_VALIDATE_BOOLEAN) ? '../' . $env->get('RENDERER_CACHE_DIRECTORY') : null
);

// CONTAINER CREATION

$request         = (new ServerRequestFromGlobalsFactory())->createServerRequestFromGlobals();
$responseFactory = new ResponseFactory();
$streamFactory   = new StreamFactory();

$router = (new RouterFactory())->createRouter(require 'routes.php');
$routingMiddleware = (new RoutingMiddlewareFactory())->createRoutingMiddleware($router, $responseFactory);

$container = (new ContainerFactory())->createContainer([
    'env' => $env,
    'session' => new Session(),
    'request' => $request,
    'responseFactory' => new ResponseFactory(),
    'streamFactory' => new StreamFactory(),
    'entityManager' => $entityManager,
    'renderer' => $twig,
    'uriGenerator' => fn() => $router->getUriGenerator()
]);

// Dispatch

$dispatcher = (new DispatcherFactory())->createDispatcher([
    $routingMiddleware,
    new RouteTargetResolverMiddleware($container)
]);
$response = $dispatcher->handle($request);


// Emitt response

http_response_code($response->getStatusCode());

foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
$body = $response->getBody();

while (!$body->eof()) {
    echo $body->read(1024);
}
