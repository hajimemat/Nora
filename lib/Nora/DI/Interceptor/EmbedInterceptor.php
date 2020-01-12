<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\Interceptor;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Nora\DI\Aop\MethodInvocation;
use Nora\Resource\Annotation\Embed;
use Nora\Resource\ResourceInterface;
use Nora\Resource\ResourceObject;
use Rize\UriTemplate\UriTemplate;

final class EmbedInterceptor implements MethodInterceptorInterface
{
    /**
     * @var \BEAR\Resource\ResourceInterface
     */
    private $resource;
    /**
     * @var Reader
     */
    private $reader;

    public function __construct(ResourceInterface $resource)
    {
        $this->resource = clone $resource;
        $this->reader = new AnnotationReader();
    }
    /**
     * {@inheritdoc}
     *
     * @throws \BEAR\Resource\Exception\EmbedException
     */
    public function invoke(MethodInvocation $invocation)
    {
        /** @var ResourceObject $ro */
        $ro = $invocation->getThis();
        $method = $invocation->getMethod();
        $query = $this->getArgsByInvocation($invocation);
        $embeds = $this->reader->getMethodAnnotations($method);
        $this->embedResource($embeds, $ro, $query);
        return $invocation->proceed();
    }
    /**
     * @param Embed[] $embeds
     *
     * @throws EmbedException
     */
    private function embedResource(array $embeds, ResourceObject $ro, array $query)
    {
        foreach ($embeds as $embed) {
            /* @var $embed Embed */
            if (! $embed instanceof Embed) {
                continue;
            }
            try {
                $templateUri = $this->getFullUri($embed->src, $ro);

                $uri = (new UriTemplate())->expand($templateUri, $query);
                // $uri = uri_template($templateUri, $query);
                $ro->body[$embed->rel] = clone $this->resource->get->uri($uri);
            } catch (BadRequestException $e) {
                // wrap ResourceNotFound or Uri exception
                throw new EmbedException($embed->src, 500, $e);
            }
        }
    }
    private function getFullUri(string $uri, ResourceObject $ro) : string
    {
        if ($uri[0] === '/') {
            $uri = "{$ro->uri->scheme}://{$ro->uri->host}" . $uri;
        }
        return $uri;
    }
    private function getArgsByInvocation(MethodInvocation $invocation) : array
    {
        $args = $invocation->getArguments()->getArrayCopy();
        $params = $invocation->getMethod()->getParameters();
        $namedParameters = [];
        foreach ($params as $param) {
            $namedParameters[$param->name] = array_shift($args);
        }
        return $namedParameters;
    }
}
