<?php

namespace BEAR\StubJson\Resource\Page;

use BEAR\Resource\ResourceObject;
class Index_688735545 extends \BEAR\StubJson\Resource\Page\Index implements \Ray\Aop\WeavedInterface
{
    use \Ray\Aop\InterceptTrait;
    
    public function onGet() : static
    {
        return $this->_intercept(func_get_args(), __FUNCTION__);
    }
}
