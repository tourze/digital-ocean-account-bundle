<?php

namespace DigitalOceanAccountBundle\Tests\Exception;

use DigitalOceanAccountBundle\Exception\DigitalOceanException;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;

/**
 * @internal
 */
#[CoversClass(DigitalOceanException::class)]
final class DigitalOceanExceptionTest extends AbstractExceptionTestCase
{
    public function testConstruct(): void
    {
        $exception = new DigitalOceanException();
        self::assertSame('DigitalOcean 操作失败', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
    }

    public function testConstructWithCustomMessage(): void
    {
        $message = '自定义错误消息';
        $exception = new DigitalOceanException($message);
        self::assertSame($message, $exception->getMessage());
    }

    public function testConstructWithCustomCode(): void
    {
        $code = 500;
        $exception = new DigitalOceanException('错误', $code);
        self::assertSame($code, $exception->getCode());
    }

    public function testConstructWithPreviousException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new DigitalOceanException('错误', 0, $previous);
        self::assertSame($previous, $exception->getPrevious());
    }
}
