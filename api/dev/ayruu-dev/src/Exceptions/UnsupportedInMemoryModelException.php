<?php

declare(strict_types=1);

namespace Dev\Ayruu\App\Exceptions;

use RuntimeException;

final class UnsupportedInMemoryModelException extends RuntimeException
{
    private const CODE = 0002;

    public function __construct(string $model = null, mixed ...$args)
    {
        parent::__construct(sprintf('InMemory repository does not support the model %s.', $model), self::CODE, ...$args);
    }
}
