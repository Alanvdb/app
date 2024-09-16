<?php declare(strict_types=1);

namespace AlanVdb\App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use AlanVdb\Renderer\Definition\RendererInterface;
use AlanVdb\Router\Definition\UriGeneratorInterface;

abstract class AbstractController
{
    protected ServerRequestInterface   $request;
    protected RendererInterface        $renderer;
    protected ResponseFactoryInterface $responseFactory;
    protected StreamFactoryInterface   $streamFactory;
    protected UriGeneratorInterface    $uriGenerator;

    public function __construct(
        ServerRequestInterface   $request,
        RendererInterface        $renderer,
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface   $streamFactory,
        UriGeneratorInterface    $uriGenerator
    ) {
        $this->request = $request;
        $this->renderer = $renderer;
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->uriGenerator = $uriGenerator;

        if (method_exists($this, 'setup')) {
            $this->setup();
        }
    }

    protected function render(string $template, array $vars = []) : string
    {
        return $this->renderer->render($template, $vars);
    }

    protected function createResponse(string|StreamInterface $view, int $code = 200) : ResponseInterface
    {
        $response = $this->responseFactory->createResponse($code);
        if (is_string($view)) {
            $view = $this->streamFactory->createStream($view);
        }
        return $response->withBody($view)->withHeader('Content', 'text/html');
    }
}
