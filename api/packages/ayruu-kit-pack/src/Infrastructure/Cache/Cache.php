<?php

declare(strict_types=1);

namespace Package\AyruuKit\App\Infrastructure\Cache;

use Package\AyruuKit\App\Traits\Classes\AdapterTrait;
use Psr\Cache\CacheItemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\CacheItem;

final class Cache implements CacheInterface
{
    use AdapterTrait;
    public const PARENT = AbstractAdapter::class;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(private AbstractAdapter $parent) {}

    /**
     * @codeCoverageIgnore - forwarding to parent
     */
    public function getItem(mixed $key): CacheItem
    {
        return $this->parent->getItem($key);
    }

    /**
     * @param array<string> $keys
     *
     * @codeCoverageIgnore - forwarding to parent
     */
    public function getItems(array $keys = []): iterable
    {
        return $this->parent->getItems($keys);
    }

    /**
     * @codeCoverageIgnore - forwarding to parent
     */
    public function clear(string $prefix = ''): bool
    {
        return $this->parent->clear($prefix);
    }

    /**
     * @param array<mixed> $metadata
     *
     * @codeCoverageIgnore - forwarding to parent
     */
    public function get(string $key, callable $callback, float $beta = null, array &$metadata = null): mixed
    {
        return $this->parent->get($key, $callback, $beta, $metadata);
    }

    /**
     * @codeCoverageIgnore - forwarding to parent
     */
    public function delete(string $key): bool
    {
        return $this->parent->delete($key);
    }

    /**
     * @codeCoverageIgnore - forwarding to parent
     */
    public function hasItem(string $key): bool
    {
        return $this->parent->hasItem($key);
    }

    /**
     * @codeCoverageIgnore - forwarding to parent
     */
    public function deleteItem(string $key): bool
    {
        return $this->parent->deleteItem($key);
    }

    /**
     * @param array<string> $keys
     *
     * @codeCoverageIgnore - forwarding to parent
     */
    public function deleteItems(array $keys): bool
    {
        return $this->parent->deleteItems($keys);
    }

    /**
     * @codeCoverageIgnore - forwarding to parent
     */
    public function save(CacheItemInterface $item): bool
    {
        return $this->parent->save($item);
    }

    /**
     * @codeCoverageIgnore - forwarding to parent
     */
    public function saveDeferred(CacheItemInterface $item): bool
    {
        return $this->parent->saveDeferred($item);
    }

    /**
     * @codeCoverageIgnore - forwarding to parent
     */
    public function commit(): bool
    {
        return $this->parent->commit();
    }

    /**
     * @codeCoverageIgnore - forwarding to parent
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->parent->setLogger($logger);
    }

    /**
     * @codeCoverageIgnore - forwarding to parent
     */
    public function reset(): void
    {
        $this->parent->reset();
    }
}
