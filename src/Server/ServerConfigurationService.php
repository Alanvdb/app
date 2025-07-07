<?php declare(strict_types=1);

namespace AlanVdb\Server;

use Psr\Container\ContainerInterface;
use AlanVdb\Server\Definition\DotEnvLoaderInterface;
use AlanVdb\Server\Definition\ServerConfigurationInterface;
use AlanVdb\Server\Definition\DotEnvLoaderFactoryInterface;
use AlanVdb\Server\Definition\ServerConfigurationFactoryInterface;
use AlanVdb\Server\Exception\ServiceNotFound;

class ServerConfigurationService implements ContainerInterface
{
    protected string $root;
    protected DotEnvLoaderFactoryInterface $loaderFactory;
    protected ServerConfigurationFactoryInterface $configFactory;
    protected ?DotEnvLoaderInterface $loader = null;
    protected ?ServerConfigurationInterface $config = null;

    /**
     * @param string $root The root directory of the server.
     * @param DotEnvLoaderFactoryInterface $loaderFactory The factory for creating DotEnv loaders.
     * @param ServerConfigurationFactoryInterface $configFactory The factory for creating DotEnv configurations.
     */
    public function __construct(
        string $root,
        DotEnvLoaderFactoryInterface $loaderFactory, 
        ServerConfigurationFactoryInterface $configFactory
    ) {
        $this->root = $root;
        $this->loaderFactory = $loaderFactory;
        $this->configFactory = $configFactory;
    }

    /**
     * Retrieves a service by its identifier.
     * 
     * @param string $id The identifier of the service.
     * @return ServerConfigurationInterface|DotEnvLoaderInterface The requested service.
     * @throws ServiceNotFound If the service is not found.
     */
    public function get(string $id): ServerConfigurationInterface|DotEnvLoaderInterface
    {
        if ($id === ServerConfigurationInterface::class) {
            if ($this->config === null) {
                $this->config = $this->configFactory->createServerConfig($this->root, $this->get(DotEnvLoaderInterface::class));
            }
            return $this->config;
        }

        if ($id === DotEnvLoaderInterface::class) {
            if ($this->loader === null) {
                $this->loader = $this->loaderFactory->createDotEnvLoader();
            }
            return $this->loader;
        }

        throw new ServiceNotFound(sprintf('No service found for "%s".', $id));
    }

    /**
     * Checks if a service is registered in the container.
     * 
     * @param string $id The identifier of the service.
     * @return bool True if the service is registered, false otherwise.
     */
    public function has(string $id): bool
    {
        return $id === ServerConfigurationInterface::class || $id === DotEnvLoaderInterface::class;
    }
}
