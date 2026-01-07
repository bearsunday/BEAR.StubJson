<?php

declare(strict_types=1);

namespace BEAR\FakeJson\Resource\Page;

use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    public function onGet(): static
    {
        $this->body = ['foo' => 'foo0'];

        return $this;
    }
}
