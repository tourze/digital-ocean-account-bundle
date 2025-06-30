<?php

namespace DigitalOceanAccountBundle\Tests\Exception;

use DigitalOceanAccountBundle\Exception\MissingApiKeyException;
use PHPUnit\Framework\TestCase;

class MissingApiKeyExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $exception = new MissingApiKeyException();
        self::assertSame('请求缺少API Key', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
    }

    public function testConstructWithCustomMessage(): void
    {
        $message = '自定义 API Key 错误';
        $exception = new MissingApiKeyException($message);
        self::assertSame($message, $exception->getMessage());
    }

    public function testConstructWithCustomCode(): void
    {
        $code = 401;
        $exception = new MissingApiKeyException('错误', $code);
        self::assertSame($code, $exception->getCode());
    }

    public function testConstructWithPreviousException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new MissingApiKeyException('错误', 0, $previous);
        self::assertSame($previous, $exception->getPrevious());
    }

    public function testIsRuntimeException(): void
    {
        $exception = new MissingApiKeyException();
        self::assertInstanceOf(\RuntimeException::class, $exception);
    }
}