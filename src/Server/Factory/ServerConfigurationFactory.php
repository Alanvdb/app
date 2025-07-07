<?php declare(strict_types=1);

namespace AlanVdb\Server\Factory;

use AlanVdb\Server\Definition\ServerConfigurationFactoryInterface;
use AlanVdb\Server\Definition\ServerConfigurationInterface;
use AlanVdb\Server\Definition\DotEnvLoaderInterface;
use AlanVdb\Server\ServerConfiguration;

class ServerConfigurationFactory implements ServerConfigurationFactoryInterface
{
    public function createServerConfig(
        string $root,
        DotEnvLoaderInterface $loader
    ): ServerConfigurationInterface {
        return new ServerConfiguration($root, $loader);
    }
}
