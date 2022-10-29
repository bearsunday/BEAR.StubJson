<?php

declare(strict_types=1);

namespace BEAR\StubJson;

use BEAR\Resource\ResourceObject;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

interface StubJsonInterceptorInterface extends MethodInterceptor
{
    public function invoke(MethodInvocation $invocation): ResourceObject;
}
