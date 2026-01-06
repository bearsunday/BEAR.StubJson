<?php

declare(strict_types=1);

namespace BEAR\StubJson;

use BEAR\Resource\Annotation\AppName;
use BEAR\Resource\ResourceObject;
use BEAR\StubJson\Attribute\JsonRootPath;
use Ray\Aop\MethodInvocation;
use ReflectionClass;
use stdClass;

use function assert;
use function file_exists;
use function file_get_contents;
use function json_decode;
use function sprintf;
use function str_replace;
use function strlen;
use function substr;

final class StubJsonInterceptor implements StubJsonInterceptorInterface
{
    public function __construct(
        #[AppName] private string $appName,
        #[JsonRootPath] private string $jsonRootPath
    ) {
    }

    public function invoke(MethodInvocation $invocation): ResourceObject
    {
        $ro = $invocation->getThis();
        assert($ro instanceof ResourceObject);
        $jsonPath = $this->getJsonPath($ro);
        if (! file_exists($jsonPath)) {
            $response = $invocation->proceed();
            assert($response instanceof ResourceObject);

            return $response;
        }

        $json = json_decode((string) file_get_contents($jsonPath));
        assert($json instanceof stdClass);
        $ro->body = (array) $json;

        return $ro;
    }

    private function getJsonPath(ResourceObject $ro): string
    {
        $parent = (new ReflectionClass($ro::class))->getParentClass();
        assert($parent instanceof ReflectionClass);
        $namespacePath = substr($parent->getName(), strlen($this->appName) + strlen('\Resource'));
        $path = str_replace('\\', '/', $namespacePath);

        return sprintf('%s%s.json', $this->jsonRootPath, $path);
    }
}
