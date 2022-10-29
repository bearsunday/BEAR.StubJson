<?php

namespace BEAR\StubJson\Resource\Page;

use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    public function onGet(): static
    {
        $this->body = ['foo' => 'foo0'];

        return $this;
    }
}
