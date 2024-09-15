<?php declare(strict_types=1);

namespace AlanVdb\App\Configuration\Definition;

interface DotEnvLoaderInterface
{
    public function loadFile(string $dotEnvFile);
}
