<?php declare(strict_types=1);

namespace AlanVdb\Server\Definition;

interface DotEnvLoaderFactoryInterface
{
    public function createDotEnvLoader(): DotEnvLoaderInterface;
}