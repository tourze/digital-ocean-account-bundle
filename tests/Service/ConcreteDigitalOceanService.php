<?php

namespace DigitalOceanAccountBundle\Tests\Service;

use DigitalOceanAccountBundle\Abstract\AbstractDigitalOceanService;
use DigitalOceanAccountBundle\Request\DigitalOceanRequest;

/**
 * 用于测试的具体服务类
 *
 * @internal
 */
class ConcreteDigitalOceanService extends AbstractDigitalOceanService
{
    public function testPrepareRequest(DigitalOceanRequest $request): DigitalOceanRequest
    {
        return $this->prepareRequest($request);
    }
}
