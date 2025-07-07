<?php declare(strict_types=1);

namespace AlanVdb\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

use AlanVdb\Router\UriGeneratorResolver;

abstract class AbstractController
{
    protected ContainerInterface $container;
    protected ServerRequestInterface $request;
    protected $session;
    protected $env;

    public function __construct(ContainerInterface $container)
    {
        foreach(['request', 'session', 'env'] as $attribute) {
            $this->$attribute = $container->get($attribute);
        }
        $this->container = $container;
    }

    protected function render(string $template, array $params = []): string
    {
        return $this->container->get('renderer')->render($template, $params);
    }

    protected function view(string|StreamInterface $body, int $status = 200): ResponseInterface
    {        
        if (is_string($body)) {
            $streamFactory = $this->container->get('streamFactory');
            $body = $streamFactory->createStream($body);
        }
        return $this->container->get('responseFactory')->createResponse($status)
            ->withBody($body)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    protected function getCommonTemplateParams() : array
    {
        return [
            'session' => $this->container->get('session'),
            'uriGenerator' => $this->container->get('uriGenerator')
        ];
    }
}
