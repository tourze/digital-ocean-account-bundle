<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Tests\Helper;

use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Mock HTTP 响应类，用于测试
 *
 * @internal
 */
final class MockResponse implements ResponseInterface
{
    public function __construct(
        private readonly string $content,
        private readonly int $statusCode = 200,
        /** @var array<string, list<string>> */
        private readonly array $headers = [],
    ) {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(bool $throw = true): array
    {
        return $this->headers;
    }

    public function getContent(bool $throw = true): string
    {
        return $this->content;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(bool $throw = true): array
    {
        $decoded = json_decode($this->content, true);
        if (\JSON_ERROR_NONE !== json_last_error()) {
            throw new \JsonException('无法解析JSON响应');
        }

        if (!is_array($decoded)) {
            return [];
        }

        /** @var array<string, mixed> $decoded */
        return $decoded;
    }

    public function cancel(): void
    {
        // Mock implementation - no action needed
    }

    public function getInfo(?string $type = null): mixed
    {
        /** @var array<string, mixed> $info */
        $info = [
            'response_headers' => $this->headers,
            'http_code' => $this->statusCode,
            'url' => 'https://api.digitalocean.com/v2/test',
        ];

        if (null === $type) {
            return $info;
        }

        return $info[$type] ?? null;
    }
}
