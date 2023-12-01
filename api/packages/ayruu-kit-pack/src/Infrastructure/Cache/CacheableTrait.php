<?php

declare(strict_types=1);

namespace Package\AyruuKit\App\Infrastructure\Cache;

/**
 * A trait to make the cache instances simpler to create.
 */
trait CacheableTrait
{
    protected ?CacheInterface $cache = null;

    public function getCache(): CacheInterface
    {
        return $this->cache ?? $this->setCache(null)->getCache();
    }

    public function setCache(CacheInterface $cache = null): self
    {
        $this->cache = $cache ?? new NullCache();

        return $this;
    }
}
