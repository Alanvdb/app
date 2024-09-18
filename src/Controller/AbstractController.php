<?php declare(strict_types=1);

namespace AlanVdb\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use AlanVdb\Router\Definition\UriGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment as TwigEnvironment;

abstract class AbstractController
{
    public function __construct(
        protected ServerRequestInterface   $request,
        protected ResponseFactoryInterface $responseFactory,
        protected StreamFactoryInterface   $streamFactory,
        protected EntityManagerInterface   $entityManager,
        protected TwigEnvironment          $twig
    ) {}

    protected function createResponse(string|StreamInterface $view, int $code = 200) : ResponseInterface
    {
        $response = $this->responseFactory->createResponse($code);

        if (is_string($view)) {
            $view = $this->streamFactory->createStream($view);
        }
        return $response->withBody($view)->withHeader('Content', 'text/html');
    }
}
