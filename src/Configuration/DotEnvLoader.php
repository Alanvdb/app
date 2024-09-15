<?php declare(strict_types=1);

namespace AlanVdb\App\Configuration;

use AlanVdb\App\Configuration\Definition\DotEnvLoaderInterface;
use Dotenv\Dotenv;
use AlanVdb\App\Configuration\Exception\InvalidConfigurationProvided;
use Exception;

class DotEnvLoader implements DotEnvLoaderInterface
{
    protected $env;

    public function loadFile(string $dotEnvFile)
    {
        try {
            $this->env = Dotenv::createImmutable(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');
            $this->env->load();
            $this->assertConfig();
        } catch(Exception $e) {
            throw new InvalidConfigurationProvided($e->getMessage());
        }
    }

    /**
     * @throws RuntimeException
     * @return true
     */
    protected function assertConfig()
    {
        $this->env->required('DEBUG_MODE')->isBoolean();

        // DATABASE DRIVER CONFIG CHECKINGS
        $this->env->required('DB_DRIVER')->allowedValues(['sqlite', 'mysql']);
        $dbDriver = 'pdo_' . $_ENV['DB_DRIVER'];

        if ($dbDriver === 'pdo_sqlite') {
            $this->env->required('DB_PATH')->notEmpty();
        } elseif ($dbDriver === 'pdo_mysql') {
            $this->env->required('DB_HOST')->notEmpty();
            $this->env->required('DB_NAME')->notEmpty();
            $this->env->required('DB_USER')->notEmpty();
            $this->env->required('DB_PASSWORD');
        }

        // ORM CONFIG CHECKINGS
        $this->env->required('ENTITY_DIRECTORIES')->notEmpty();
        $entityDirectories = explode(',', $_ENV['ENTITY_DIRECTORIES']);
        $this->env->required('ORM_PROXY_NAMESPACE')->notEmpty();
        $this->env->required('ORM_PROXY_DIRECTORY')->notEmpty();
    }
}
