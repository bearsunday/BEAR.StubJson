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
        $fakeJsonDir = __DIR__ . '/json';
        $injector = new Injector(new class ($fakeJsonDir) extends AbstractModule {
            public function __construct(
                public string $fakeJsonDir
            ) {
            }

            protected function configure(): void
            {
                $this->bind(Index::class);
                $this->install(new FakeJsonModule($this->fakeJsonDir));
                $this->install(new ResourceModule('BEAR\FakeJson'));
            }
        }, __DIR__ . '/tmp');
        $ro = $injector->getInstance(Index::class);
        $ro->onGet();
        $this->assertSame(['foo' => 'foo1'], $ro->body);
    }
}
