<?php declare(strict_types=1);

namespace AlanVdb\Server\Definition;

interface ServerConfigurationFactoryInterface
{
    public function createServerConfig(string $root, DotEnvLoaderInterface $loader): ServerConfigurationInterface;
}