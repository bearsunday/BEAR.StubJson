<?php

declare(strict_types=1);

namespace BEAR\FakeJson;

use BEAR\FakeJson\Attribute\FakeJsonDir;
use BEAR\Resource\ResourceObject;
use Ray\Di\AbstractModule;

final class FakeJsonModule extends AbstractModule
{
    public function __construct(private string $jsonPath, ?AbstractModule $module = null)
    {
        parent::__construct($module);
    }

    protected function configure(): void
    {
        $this->bind()->annotatedWith(FakeJsonDir::class)->toInstance($this->jsonPath);
        $this->bind(FakeJsonInterceptorInterface::class)->to(FakeJsonInterceptor::class);
        $this->bindInterceptor(
            $this->matcher->subclassesOf(ResourceObject::class),
            $this->matcher->startsWith('on'),
            [FakeJsonInterceptorInterface::class]
        );
    }
}
