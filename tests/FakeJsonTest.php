<?php

declare(strict_types=1);

namespace BEAR\FakeJson;

use BEAR\FakeJson\Resource\Page\Index;
use BEAR\Resource\Module\ResourceModule;
use PHPUnit\Framework\TestCase;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

class FakeJsonTest extends TestCase
{
    public function testFakeJsonReturned(): void
    {
        $fakeJsonPath = __DIR__ . '/json';
        $injector = new Injector(new class ($fakeJsonPath) extends AbstractModule {
            public function __construct(
                public string $fakeJsonPath
            ) {
            }

            protected function configure(): void
            {
                $this->bind(Index::class);
                $this->install(new FakeJsonModule($this->fakeJsonPath));
                $this->install(new ResourceModule('BEAR\FakeJson'));
            }
        }, __DIR__ . '/tmp');
        $ro = $injector->getInstance(Index::class);
        $ro->onGet();
        $this->assertSame(['foo' => 'foo1'], $ro->body);
    }
}
