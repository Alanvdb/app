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
use AlanVdb\Router\RouteCollector;

use Doctrine\ORM\EntityManagerInterface;
use AlanVdb\ORM\Manager\Factory\DoctrineEntityManagerFactory;

use Twig\Loader\FilesystemLoader as TwigFilesystemLoader;
use Twig\Environment             as TwigEnvironment;

use AlanVdb\App\Configuration\Exception\InvalidConfigurationProvided;


const ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..';

require ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

(new DotEnvLoaderFactory())->createDotEnvLoader()->load(ROOT);

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
    $collector = new RouteCollector($c);
    $routes = $collector->collectRoutes(require ROOT . DIRECTORY_SEPARATOR . $_ENV['ROUTES_CONFIG']);
});

$container->add(RequestMatcherInterface::class, function($c) {
    return $c->get(RouterFactory::class)->createRequestMatcher($c->get(RouteCollection::class));
});

$container->add(UriGeneratorInterface::class, function($c) {
    return $c->get(RouterFactory::class)->createUriGenerator($c->get(RouteCollection::class));
});



// ENTITY MANAGER

$container->add(EntityManagerInterface::class, function()
{
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

    return (new DoctrineEntityManagerFactory())->createEntityManager(
        $dbParams,
        explode(',', $_ENV['ENTITY_DIRECTORIES']),
        $_ENV['MODEL_PROXY_DIRECTORY'],
        $_ENV['MODEL_PROXY_NAMESPACE'],
        boolval($_ENV['DEBUG_MODE'])
    );
});



// RENDERER

$container->add(TwigEnvironment::class, function() {

    if (!array_key_exists('TEMPLATES_DIRECTORY', $_ENV) )

    $loader = new TwigFilesystemLoader($_ENV['TEMPLATES_DIRECTORY']);
    return new TwigEnvironment($loader, [
        'cache' => $_ENV['RENDERER_CACHE_DIRECTORY'],
    ]);
});
