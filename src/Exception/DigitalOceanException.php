<?php

namespace DigitalOceanAccountBundle\Exception;

/**
 * DigitalOcean 通用异常
 */
class DigitalOceanException extends \RuntimeException
{
    public function __construct(string $message = 'DigitalOcean 操作失败', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
