<?php declare(strict_types=1);

namespace App;

use Dotenv\Dotenv;
use AlanVdb\Dispatcher\Factory\DispatcherFactory;
use AlanVdb\Middleware\ModuleLoaderMiddleware;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\ServerRequest;
use AlanVdb\Router\Factory\RouterFactory;
use AlanVdb\ORM\Manager\Factory\DoctrineEntityManagerFactory;
use AlanVdb\Renderer\Factory\TwigFactory;


const ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..';

require ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';


// .env loading

Dotenv::createImmutable(ROOT)->load();

const REQUIRED_VARS = [
    'DEBUG_MODE',
    'ROUTES_CONFIG',
    'DB_DRIVER',
    'ENTITY_DIRECTORIES',
    'MODEL_PROXY_NAMESPACE',
    'MODEL_PROXY_DIRECTORY',
    'TEMPLATES_DIRECTORY',
    'ACTIVATE_TEMPLATE_CACHE'
];

const DB_DRIVER_PARAMS = [
    'sqlite' => ['DB_PATH'],
    'mysql' => ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD']
];

foreach (REQUIRED_VARS as $varName) {
    if (!array_key_exists($varName, $_ENV)) {
        throw new InvalidConfigurationProvided("Missing .env parameter : '$varName'.");
    }
}

foreach (DB_DRIVER_PARAMS[$_ENV['DB_DRIVER']] as $varName) {
    if (!array_key_exists($varName, $_ENV)) {
        throw new InvalidConfigurationProvided("Missing .env parameter : '$varName'.");
    }
}

if (filter_var($_ENV['ACTIVATE_TEMPLATE_CACHE'], FILTER_VALIDATE_BOOLEAN)) {
    if (!array_key_exists('RENDERER_CACHE_DIRECTORY', $_ENV)) {
        throw new InvalidConfigurationProvided("Missing .env parameter : 'RENDERER_CACHE_DIRECTORY'.");
    }
}


// doctrine

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



// dispatch

$request       = ServerRequest::fromGlobals();
$httpFactory   = new HttpFactory();
$routerFactory = new RouterFactory();

$twig = (new TwigFactory())->createRenderer(
    $_ENV['TEMPLATES_DIRECTORY'],
    filter_var($_ENV['ACTIVATE_TEMPLATE_CACHE'], FILTER_VALIDATE_BOOLEAN) ? $_ENV['RENDERER_CACHE_DIRECTORY'] : null
);

$dispatcher = (new DispatcherFactory())->createDispatcher([
    $routerFactory->createRouterMiddleware(require 'routes.php'),
    new ModuleLoaderMiddleware(
        $httpFactory,
        $httpFactory,
        $entityManager,
        $twig
    )
]);
$response = $dispatcher->handle($request);


// EMIT RESPONSE

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
