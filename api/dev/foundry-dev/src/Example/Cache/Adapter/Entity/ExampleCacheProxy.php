<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Example\Cache\Adapter\Entity;

use Dev\Foundry\App\Adapter\Entity\AbstractFoundryProxy;

/**
 * @extends AbstractFoundryProxy<Example>
 */
final class ExampleCacheProxy extends AbstractFoundryProxy
{
    public function __construct(mixed ...$attributes)
    {
        parent::__construct(Example::class, ...$attributes);
    }
}
