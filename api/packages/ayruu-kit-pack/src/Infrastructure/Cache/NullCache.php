<?php

declare(strict_types=1);

namespace Package\AyruuKit\App\Infrastructure\Cache;

use Package\AyruuKit\App\Traits\Classes\LogAwareTrait;
use Symfony\Component\Cache\Adapter\NullAdapter;

/**
 * @internal
 */
final class NullCache extends NullAdapter implements CacheInterface
{
    use LogAwareTrait;

    /**
     * @codeCoverageIgnore
     */
    public function reset(): void {}
}
