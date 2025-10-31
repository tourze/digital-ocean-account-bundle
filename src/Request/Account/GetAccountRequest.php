<?php

namespace DigitalOceanAccountBundle\Request\Account;

use DigitalOceanAccountBundle\Request\DigitalOceanRequest;

/**
 * 获取账号信息请求
 *
 * @see https://docs.digitalocean.com/reference/api/digitalocean/#tag/Account/operation/account_get
 */
class GetAccountRequest extends DigitalOceanRequest
{
    public function getRequestPath(): string
    {
        return '/account';
    }
}
