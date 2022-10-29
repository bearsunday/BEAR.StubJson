<?php

declare(strict_types=1);

namespace BEAR\StubJson;

use BEAR\Resource\Module\ResourceModule;
use BEAR\StubJson\Resource\Page\Index;
use PHPUnit\Framework\TestCase;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

class StubJsonTest extends TestCase
{
    public function testA(): void
    {
        $exampleJsonPath = __DIR__ . '/fakeJson';
        $injector = new Injector(new class ($exampleJsonPath) extends AbstractModule {
            public function __construct(
                public string $exampleJsonPath
            ) {
            }

            protected function configure(): void
            {
                $this->bind(Index::class);
                $this->install(new StubJsonModule($this->exampleJsonPath));
                $this->install(new ResourceModule('BEAR\StubJson'));
            }
        }, __DIR__ . '/tmp');
        $ro = $injector->getInstance(Index::class);
        $ro->onGet();
        $this->assertSame(['foo' => 'foo1'], $ro->body);
    }
}
