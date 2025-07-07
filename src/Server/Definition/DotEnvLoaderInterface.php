<?php declare(strict_types=1);

namespace AlanVdb\Server\Definition;

interface DotEnvLoaderInterface
{
    public function load(string $root): void;
}
