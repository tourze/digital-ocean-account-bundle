<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Tests\Helper;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

/**
 * 测试用的简化菜单项类
 * 仅实现测试中使用的核心方法，其他方法抛出异常表示未实现
 *
 * @internal
 */
final class TestMenuItem implements ItemInterface
{
    private string $name = '';

    /** @var array<string, ItemInterface> */
    private array $children = [];

    private ?ItemInterface $parent = null;

    /** @phpstan-ignore symplify.noReturnSetterMethod */
    public function setName(string $name): ItemInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addChild(mixed $child, ?array $options = null): ItemInterface
    {
        if ($child instanceof ItemInterface) {
            $child->setParent($this);
            $this->children[$child->getName()] = $child;
        }

        return $this;
    }

    /** @phpstan-ignore symplify.noReturnSetterMethod */
    public function setParent(?ItemInterface $parent = null): ItemInterface
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): ?ItemInterface
    {
        return $this->parent;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    // 所有其他方法都是为了满足接口要求，但在测试中不使用
    /** @phpstan-ignore symplify.noReturnSetterMethod */
    public function setUri(?string $uri): ItemInterface
    {
        return $this;
    }

    public function getUri(): ?string
    {
        throw new \RuntimeException('Not implemented');
    }

    public function setLabel(?string $label): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getLabel(): string
    {
        return $this->name;
    }

    public function getExtras(): array
    {
        return [];
    }

    public function setExtras(array $extras): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getExtra(string $name, mixed $default = null): mixed
    {
        return $default;
    }

    public function setExtra(string $name, mixed $value): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getAttributes(): array
    {
        return [];
    }

    public function setAttributes(array $attributes): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $default;
    }

    /** @phpstan-ignore symplify.noReturnSetterMethod */
    public function setAttribute(string $name, mixed $value): ItemInterface
    {
        return $this;
    }

    public function getLinkAttributes(): array
    {
        return [];
    }

    public function setLinkAttributes(array $linkAttributes): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getLinkAttribute(string $name, mixed $default = null): mixed
    {
        return $default;
    }

    public function setLinkAttribute(string $name, mixed $value): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getChildrenAttributes(): array
    {
        return [];
    }

    public function setChildrenAttributes(array $childrenAttributes): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getChildrenAttribute(string $name, mixed $default = null): mixed
    {
        return $default;
    }

    public function setChildrenAttribute(string $name, mixed $value): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getLabelAttributes(): array
    {
        return [];
    }

    public function setLabelAttributes(array $labelAttributes): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getLabelAttribute(string $name, mixed $default = null): mixed
    {
        return $default;
    }

    public function setLabelAttribute(string $name, mixed $value): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getChild(string $name): ?ItemInterface
    {
        return $this->children[$name] ?? null;
    }

    public function setChildren(array $children): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function removeChild($name): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function isDisplayed(): bool
    {
        return true;
    }

    public function setDisplay(bool $bool): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getDisplayChildren(): bool
    {
        return true;
    }

    public function setDisplayChildren(bool $bool): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }

    public function isFirst(): bool
    {
        return true;
    }

    public function isLast(): bool
    {
        return true;
    }

    public function isRoot(): bool
    {
        return null === $this->parent;
    }

    public function getLevel(): int
    {
        return 0;
    }

    public function getRoot(): ItemInterface
    {
        if ($this->isRoot()) {
            return $this;
        }
        if (null === $this->parent) {
            return $this;
        }

        return $this->parent->getRoot();
    }

    public function isCurrent(): bool
    {
        return false;
    }

    public function setCurrent(?bool $bool): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function isAncestor(): bool
    {
        return false;
    }

    public function count(): int
    {
        return count($this->children);
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->children);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->children[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->children[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \RuntimeException('Not implemented');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getPathAsString(string $separator = ' > '): string
    {
        return $this->name;
    }

    public function copy(): ItemInterface
    {
        return clone $this;
    }

    /**
     * @param array<string, mixed> $routeParameters
     * @param array<string, mixed> $routeAbsolute
     */
    public function setRoute(?string $route, array $routeParameters = [], array $routeAbsolute = []): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getRoute(): null
    {
        return null;
    }

    /** @return array<string, mixed> */
    public function getRouteParameters(): array
    {
        return [];
    }

    public function getRouteAbsolute(): bool
    {
        return false;
    }

    public function setFactory(?FactoryInterface $factory): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getFactory(): null
    {
        return null;
    }

    public function moveToPosition(int $position): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function moveToFirstPosition(): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function moveToLastPosition(): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function moveChildToPosition(ItemInterface $child, int $position): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    /** @param array<int|string, string> $order */
    public function reorderChildren(array $order): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function isLinkElement(): bool
    {
        return true;
    }

    public function setLinkElement(bool $bool): ItemInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getHtml(?string $options = null, ?string $childrenOptions = null): string
    {
        return '';
    }

    /** @param array<string, mixed>|null $options */
    public function renderLink(?array $options = null): string
    {
        return '';
    }

    /** @param array<string, mixed>|null $options */
    public function renderLabel(?array $options = null): string
    {
        return $this->getLabel();
    }

    /** @param array<string, mixed>|null $options */
    public function renderChildren(?array $options = null): string
    {
        return '';
    }

    public function actsLikeFirst(): bool
    {
        return true;
    }

    public function actsLikeLast(): bool
    {
        return true;
    }

    public function getFirstChild(): ItemInterface
    {
        if (count($this->children) > 0) {
            $first = reset($this->children);

            return false !== $first ? $first : $this;
        }

        return $this;
    }

    public function getLastChild(): ItemInterface
    {
        if (count($this->children) > 0) {
            $last = end($this->children);

            return false !== $last ? $last : $this;
        }

        return $this;
    }
}
