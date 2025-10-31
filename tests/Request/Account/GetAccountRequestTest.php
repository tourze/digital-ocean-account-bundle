<?php

namespace DigitalOceanAccountBundle\Tests\Request\Account;

use DigitalOceanAccountBundle\Request\Account\GetAccountRequest;
use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(GetAccountRequest::class)]
final class GetAccountRequestTest extends RequestTestCase
{
    public function testGetRequestPathReturnsAccountPath(): void
    {
        $request = new GetAccountRequest();

        $this->assertEquals('/account', $request->getRequestPath());
    }
}
