<?php declare(strict_types=1);

namespace AlanVdb\Router;

use AlanVdb\Router\Definition\UriGeneratorInterface;

class UriGeneratorResolver
{
    protected UriGeneratorInterface $generator;

    public function __construct(UriGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function __invoke(string $name, array $vars = []) : string
    {
        return $this->generator->generateUri($name, $vars);
    }
}
