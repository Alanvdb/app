<?php declare(strict_types=1);

namespace AlanVdb\App\Configuration;

use AlanVdb\App\Configuration\Definition\DotEnvLoaderInterface;
use Dotenv\Dotenv;

use AlanVdb\App\Configuration\Exception\InvalidConfigurationProvided;

class DotEnvLoader implements DotEnvLoaderInterface
{
    protected $env;

    protected const REQUIRED = [
        'DEBUG_MODE',
        'ROUTES_CONFIG',
        'DB_DRIVER',
        'ENTITY_DIRECTORIES',
        'MODEL_PROXY_NAMESPACE',
        'MODEL_PROXY_DIRECTORY',
        'TEMPLATES_DIRECTORY',
        'ACTIVATE_TEMPLATE_CACHE'
    ];

    protected const DB_DRIVER_PARAMS = [
        'sqlite' => ['DB_PATH'],
        'mysql' => ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD']
    ];

    /**
     * @throws InvalidConfigurationProvided
     */
    public function load(string $root) : void
    {
        if (!is_dir($root)) {
            throw new InvalidConfigurationProvided("Root path provided is not a valid directory : '$root'.");
        }
        $this->env = Dotenv::createImmutable($root);
        $this->env->load();
        $this->assertConfig();
    }

    protected function assertConfig() : void
    {
        foreach (self::REQUIRED as $varName) {
            if (!array_key_exists($varName, $_ENV)) {
                throw new InvalidConfigurationProvided("Missing .env parameter : '$varName'.");
            }
        }
        foreach (self::DB_DRIVER_PARAMS[$_ENV['DB_DRIVER']] as $varName) {
            if (!array_key_exists($varName, $_ENV)) {
                throw new InvalidConfigurationProvided("Missing .env parameter : '$varName'.");
            }
        }

        if (filter_var($_ENV['ACTIVATE_TEMPLATE_CACHE'], FILTER_VALIDATE_BOOLEAN)) {
            if (!array_key_exists('RENDERER_CACHE_DIRECTORY', $_ENV)) {
                throw new InvalidConfigurationProvided("Missing .env parameter : 'RENDERER_CACHE_DIRECTORY'.");
            }
        }
    }
}
