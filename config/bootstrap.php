<?php declare(strict_types=1);

namespace App;

use AlanVdb\App\Configuration\Factory\DotEnvLoaderFactory;
use AlanVdb\Dispatcher\Factory\DispatcherFactory;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\ServerRequest;

use AlanVdb\Router\Factory\RouterFactory;

use Doctrine\ORM\EntityManagerInterface;
use AlanVdb\ORM\Manager\Factory\DoctrineEntityManagerFactory;

use Twig\Loader\FilesystemLoader as TwigFilesystemLoader;
use Twig\Environment             as TwigEnvironment;


const ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..';

require ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

(new DotEnvLoaderFactory())->createDotEnvLoader()->load(ROOT);

// ROUTER
$request = ServerRequest::fromGlobals();
$httpFactory = new HttpFactory();
$routerFactory = new RouterFactory();

// ROUTES
$routeParams = require 'routes.php';
$routes = [];

foreach ($routeParams as $params) {
    $routes[] = $routerFactory->createRoute(...$params);
}
$routeIterator = $routerFactory->createRouteIterator(...$routes);

// DISPATCHING
$matcher = $routerFactory->createRequestMatcher($routeIterator);
$route   = $matcher->matchRequest($request);

// DOCTRINE
$dbDriver = 'pdo_' . $_ENV['DB_DRIVER'];

if ($dbDriver === 'pdo_sqlite') {
    $dbParams = ['path' => $_ENV['DB_PATH']];
} elseif ($dbDriver === 'pdo_mysql') {
    $dbParams = [
        'host' => $_ENV['DB_HOST'],
        'dbname' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ];
}
$dbParams['driver'] = $dbDriver;

$entityManager = (new DoctrineEntityManagerFactory())->createEntityManager(
    $dbParams,
    explode(',', $_ENV['ENTITY_DIRECTORIES']),
    $_ENV['MODEL_PROXY_DIRECTORY'],
    $_ENV['MODEL_PROXY_NAMESPACE'],
    boolval($_ENV['DEBUG_MODE'])
);

// TWIG
$twigLoader = new TwigFilesystemLoader($_ENV['TEMPLATES_DIRECTORY']);
$twig = new TwigEnvironment($twigLoader, [
    'cache' => $_ENV['RENDERER_CACHE_DIRECTORY']
]);

// RESPONSE
list($controller, $method) = $route->getTarget();
$response = (new $controller(
    $request,
    $httpFactory,
    $httpFactory,
    $routerFactory->createUriGenerator($routeIterator),
    $entityManager,
    $twig
))->$method($route->getParams());

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
