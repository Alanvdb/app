<?php declare(strict_types=1);

namespace AlanVdb\App\Configuration\Definition;

interface DotEnvLoaderFactoryInterface
{
    public function createDotEnvLoader() : DotEnvLoaderInterface;
}
