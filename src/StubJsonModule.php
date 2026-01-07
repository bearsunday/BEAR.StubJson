<?php

declare(strict_types=1);

namespace BEAR\StubJson;

use BEAR\Resource\ResourceObject;
use BEAR\StubJson\Attribute\JsonRootPath;
use Ray\Di\AbstractModule;

final class StubJsonModule extends AbstractModule
{
    public function __construct(private string $jsonPath, ?AbstractModule $module = null)
    {
        parent::__construct($module);
    }

    protected function configure(): void
    {
        $this->bind()->annotatedWith(JsonRootPath::class)->toInstance($this->jsonPath);
        $this->bind(StubJsonInterceptorInterface::class)->to(StubJsonInterceptor::class);
        $this->bindInterceptor(
            $this->matcher->subclassesOf(ResourceObject::class),
            $this->matcher->startsWith('on'),
            [StubJsonInterceptorInterface::class]
        );
    }
}
