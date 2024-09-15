<?php declare(strict_types=1);

namespace AlanVdb\App\Configuration\Factory;

use AlanVdb\App\Configuration\Definition\DotEnvLoaderFactoryInterface;
use AlanVdb\App\Configuration\Definition\DotEnvLoaderInterface;
use AlanVdb\App\Configuration\DotEnvLoader;

class DotEnvLoaderFactory implements DotEnvLoaderFactoryInterface
{
    public function createDotEnvLoader() : DotEnvLoaderInterface
    {
        return new DotEnvLoader();
    }
}
