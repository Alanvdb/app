<?php declare(strict_types=1);

namespace AlanVdb\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use AlanVdb\Renderer\Definition\RendererInterface;
use AlanVdb\Router\Definition\UriGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractController
{
    public function __construct(
        protected ServerRequestInterface   $request,
        protected RendererInterface        $renderer,
        protected ResponseFactoryInterface $responseFactory,
        protected StreamFactoryInterface   $streamFactory,
        protected UriGeneratorInterface    $uriGenerator,
        protected EntityManagerInterface   $entityManager
    ) {

        if (method_exists($this, 'setup')) {
            $this->setup();
        }
    }

    protected function render(string $template, array $vars = []) : string
    {
        return $this->renderer->render($template, $vars);
    }

    protected function renderTemplates(array $templates) : StreamInterface
    {
        $stream = $this->streamFactory->createStream('');

        foreach ($templates as $template => $vars) {
            
            if (!is_array($vars) || empty($vars)) {
                $stream->write($this->render($template));
            } else {
                //var_dump($template, $vars, $this->render($template, $vars)); die(); // DEBUG
                $stream->write($this->render($template, $vars));
            }
        }
        return $stream;
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
