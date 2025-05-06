<?php

namespace DigitalOceanAccountBundle\Tests\Request\Account;

use DigitalOceanAccountBundle\Request\Account\GetAccountRequest;
use PHPUnit\Framework\TestCase;

class GetAccountRequestTest extends TestCase
{
    public function testGetRequestPath_returnsAccountPath(): void
    {
        $request = new GetAccountRequest();

        $this->assertEquals('/account', $request->getRequestPath());
    }
}
