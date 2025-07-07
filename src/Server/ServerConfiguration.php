<?php declare(strict_types=1);

namespace AlanVdb\Server;

use Psr\Container\ContainerInterface;
use AlanVdb\Server\Definition\ServerConfigurationInterface;
use AlanVdb\Server\Definition\DotEnvLoaderInterface;
use AlanVdb\Server\Exception\ServerConfigurationNotFound;

class ServerConfiguration implements ServerConfigurationInterface, ContainerInterface
{
    private string $root;

    public function __construct(string $rootDirectory, DotEnvLoaderInterface $loader)
    {
        $this->root = $rootDirectory;
        $loader->load($this->root);
    }

    public function get(string $key): string
    {
        if ($key === 'ROOT') {
            return $this->root;
        }

        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        throw new ServerConfigurationNotFound(sprintf('The key "%s" does not exist.', $key));
    }

    public function has(string $key): bool
    {
        return isset($_ENV[$key]) || isset($_SERVER[$key]) || $key === 'ROOT';
    }
}
