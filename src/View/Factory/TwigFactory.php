<?php declare(strict_types=1);

namespace AlanVdb\View\Factory;

use Twig\Loader\FilesystemLoader as TwigFilesystemLoader;
use Twig\Environment as TwigEnvironment;
use Exception;

class TwigFactory
{
    public function createRenderer(string $templateDirectory, bool $activateCache = false, string $cacheDirectory) : TwigEnvironment
    {
        if (!is_dir($templateDirectory)) {
            throw new Exception("Provided template directory does not exist : '$templateDirectory'.");
        }
        $twigLoader = new TwigFilesystemLoader($templateDirectory);
        $options = [];

        if ($activateCache) {
            if (!is_dir($cacheDirectory)) {
                throw new Exception("Provided renderer cache directory does not exist : '$cacheDirectory'.");
            }
            $options['cache'] = $cacheDirectory;
        }
        return new TwigEnvironment($twigLoader, $options);
    }
}
