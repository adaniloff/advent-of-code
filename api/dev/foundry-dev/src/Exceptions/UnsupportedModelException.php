<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Exceptions;

use RuntimeException;

final class UnsupportedModelException extends RuntimeException
{
    private const CODE = 0012;

    public function __construct(string $model = null, mixed ...$args)
    {
        parent::__construct(sprintf('Adapter repository does not support the model %s.', $model), self::CODE, ...$args);
    }
}
