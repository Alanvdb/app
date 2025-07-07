<?php declare(strict_types=1);

namespace AlanVdb\Server;

use AlanVdb\Server\Definition\DotEnvLoaderInterface;
use Dotenv\Dotenv;
use AlanVdb\Server\Exception\NonExistingRootDirectory;
use AlanVdb\Server\Exception\NonReadableRootDirectory;

class VlucasDotEnvLoader implements DotEnvLoaderInterface
{
    public function load(string $root): void
    {
        if (!is_dir($root)) {
            throw new NonExistingRootDirectory(sprintf('The directory "%s" does not exist.', $root));
        }
        if (!is_readable($root)) {
            throw new NonReadableRootDirectory(sprintf('The directory "%s" is not readable.', $root));
        }
        Dotenv::createImmutable($root)->load();
    }
}
