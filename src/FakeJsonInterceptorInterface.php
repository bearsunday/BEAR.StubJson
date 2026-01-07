<?php

declare(strict_types=1);

namespace BEAR\FakeJson;

use BEAR\Resource\ResourceObject;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

interface FakeJsonInterceptorInterface extends MethodInterceptor
{
    public function invoke(MethodInvocation $invocation): ResourceObject;
}
