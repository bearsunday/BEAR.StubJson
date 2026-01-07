<?php

declare(strict_types=1);

namespace BEAR\StubJson;

use BEAR\Resource\Module\ResourceModule;
use BEAR\StubJson\Exception\RuntimeException;
use BEAR\StubJson\Resource\Page\Index;
use PHPUnit\Framework\TestCase;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

use function file_put_contents;
use function mkdir;
use function rmdir;
use function unlink;

class StubJsonTest extends TestCase
{
    public function testStubJsonReturnsJsonFileContents(): void
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

    public function testFallbackToOriginalMethodWhenJsonFileNotFound(): void
    {
        $nonExistentJsonPath = __DIR__ . '/nonExistentJson';
        $injector = new Injector(new class ($nonExistentJsonPath) extends AbstractModule {
            public function __construct(
                public string $nonExistentJsonPath
            ) {
            }

            protected function configure(): void
            {
                $this->bind(Index::class);
                $this->install(new StubJsonModule($this->nonExistentJsonPath));
                $this->install(new ResourceModule('BEAR\StubJson'));
            }
        }, __DIR__ . '/tmp');
        $ro = $injector->getInstance(Index::class);
        $ro->onGet();
        // Original method returns ['foo' => 'foo0'], not the stub JSON ['foo' => 'foo1']
        $this->assertSame(['foo' => 'foo0'], $ro->body);
    }

    public function testThrowsExceptionForInvalidJson(): void
    {
        $invalidJsonPath = __DIR__ . '/invalidJson';
        @mkdir($invalidJsonPath . '/Page', 0777, true);
        file_put_contents($invalidJsonPath . '/Page/Index.json', 'invalid json');

        try {
            $injector = new Injector(new class ($invalidJsonPath) extends AbstractModule {
                public function __construct(
                    public string $invalidJsonPath
                ) {
                }

                protected function configure(): void
                {
                    $this->bind(Index::class);
                    $this->install(new StubJsonModule($this->invalidJsonPath));
                    $this->install(new ResourceModule('BEAR\StubJson'));
                }
            }, __DIR__ . '/tmp');

            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('Invalid JSON');

            $ro = $injector->getInstance(Index::class);
            $ro->onGet();
        } finally {
            @unlink($invalidJsonPath . '/Page/Index.json');
            @rmdir($invalidJsonPath . '/Page');
            @rmdir($invalidJsonPath);
        }
    }
}
