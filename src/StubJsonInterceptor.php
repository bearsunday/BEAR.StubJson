<?php

declare(strict_types=1);

namespace BEAR\StubJson;

use BEAR\Resource\Annotation\AppName;
use BEAR\Resource\ResourceObject;
use BEAR\StubJson\Attribute\JsonRootPath;
use BEAR\StubJson\Exception\RuntimeException;
use Ray\Aop\MethodInvocation;
use ReflectionClass;

use function assert;
use function file_exists;
use function file_get_contents;
use function gettype;
use function is_object;
use function json_decode;
use function json_last_error;
use function json_last_error_msg;
use function sprintf;
use function str_replace;
use function strlen;
use function substr;

use const JSON_ERROR_NONE;

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

        $jsonContent = (string) file_get_contents($jsonPath);
        $json = json_decode($jsonContent);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(sprintf('Invalid JSON in %s: %s', $jsonPath, json_last_error_msg()));
        }

        if (! is_object($json)) {
            throw new RuntimeException(sprintf('JSON in %s must decode to an object, got %s', $jsonPath, gettype($json)));
        }

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
