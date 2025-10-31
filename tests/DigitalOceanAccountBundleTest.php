<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Tests;

use DigitalOceanAccountBundle\DigitalOceanAccountBundle;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(DigitalOceanAccountBundle::class)]
#[RunTestsInSeparateProcesses]
final class DigitalOceanAccountBundleTest extends AbstractBundleTestCase
{
}
