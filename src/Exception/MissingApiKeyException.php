<?php

namespace DigitalOceanAccountBundle\Exception;

/**
 * API Key 缺失异常
 */
class MissingApiKeyException extends \RuntimeException
{
    public function __construct(string $message = '请求缺少API Key', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
