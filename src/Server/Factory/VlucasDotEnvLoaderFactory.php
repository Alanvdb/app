<?php declare(strict_types=1);

namespace AlanVdb\Server\Factory;

use AlanVdb\Server\Definition\DotEnvLoaderFactoryInterface;
use AlanVdb\Server\Definition\DotEnvLoaderInterface;
use AlanVdb\Server\VlucasDotEnvLoader;

class VlucasDotEnvLoaderFactory implements DotEnvLoaderFactoryInterface
{
    public function createDotEnvLoader(): DotEnvLoaderInterface
    {
        return new VlucasDotEnvLoader();
    }
}