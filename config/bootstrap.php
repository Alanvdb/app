<?php declare(strict_types=1);

namespace App;

use AlanVdb\App\Configuration\Factory\DotEnvLoaderFactory;
use AlanVdb\Dependency\Factory\ContainerFactory;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\ServerRequest;

use AlanVdb\Router\Definition\RouteFactoryInterface;
use AlanVdb\Router\Definition\UriGeneratorInterface;
use AlanVdb\Router\Definition\RequestMatcherInterface;
use AlanVdb\Router\Factory\RouterFactory;

use AlanVdb\ORM\Manager\Factory\EntityManagerFactory;
use Doctrine\ORM\EntityManager;

use AlanVdb\Renderer\Definition\RendererInterface;
use AlanVdb\Renderer\Factory\PhpRendererFactory;


const ROOT          = __DIR__ . DIRECTORY_SEPARATOR . '..';
const ROUTES_CONFIG = 'routes.php';

require ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';



// .env file loading

(new DotEnvLoaderFactory())->createDotEnvLoader()->loadFile(trim(DIRECTORY_SEPARATOR, realpath(ROOT)));



// CONTAINER

$container = (new ContainerFactory())->createLazyContainer();



// HTTP MESSAGES

$container->add(ServerRequestInterface::class, function() {
    return ServerRequest::fromGlobals();
});

$container->add(ServerRequestFactoryInterface::class, function($c) {
    return $c->get(HttpFactory::class);
});

$container->add(RequestFactoryInterface::class, function($c) {
    return $c->get(HttpFactory::class);
});

$container->add(ResponseFactoryInterface::class, function($c) {
    return $c->get(HttpFactory::class);
});

$container->add(StreamFactoryInterface::class, function($c) {
    return $c->get(HttpFactory::class);
});

$container->add(UploadedFileFactoryInterface::class, function($c) {
    return $c->get(HttpFactory::class);
});

$container->add(UriFactoryInterface::class, function($c) {
    return $c->get(HttpFactory::class);
});

$container->add(HttpFactory::class, function() {
    return new HttpFactory();
});



// ROUTER

$container->add(RouterFactory::class, function() {
    return new RouterFactory();
});

$container->add(RouteCollection::class, function($c) {
    $factory = $c->get(RouterFactory::class);
    $routeCollection = $factory->createRouteCollection();
    $routes = require ROUTES_CONFIG;

    foreach ($routes as $route) {

        $routeCollection->add($route[0], function() use($factory, $route, $c)
        {
            return $factory->createRoute($route[0], $route[1], $route[2], function($args) use($route, $c) {
                $controller = new $route[3](
                    $c->get(ServerRequestInterface::class),
                    $c->get(RendererInterface::class),
                    $c->get(HttpFactory::class),
                    $c->get(HttpFactory::class),
                    $c->get(UriGeneratorInterface::class)
                );
                $method = $route[4];
                return $controller->$method($args);
            });
        });
    }
    return $routeCollection;
});

$container->add(RequestMatcherInterface::class, function($c) {
    return $c->get(RouterFactory::class)->createRequestMatcher($c->get(RouteCollection::class));
});

$container->add(UriGeneratorInterface::class, function($c) {
    return $c->get(RouterFactory::class)->createUriGenerator($c->get(RouteCollection::class));
});



// ENTITY MANAGER

$container->add(EntityManager::class, function()
{
    $dbDriver = $_ENV['DB_DRIVER'];

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

    return (new EntityManagerFactory())->createEntityManager(
        $dbParams,
        $_ENV['ENTITY_DIRECTORIES'],
        $_ENV['ORM_PROXY_DIRECTORY'],
        $_ENV['ORM_PROXY_NAMESPACE'],
        boolval($_ENV['DEBUG_MODE'])
    );
});



// RENDERER

$container->add(RendererInterface::class, function() {
    return (new PhpRendererFactory())->createPhpRenderer();
});
