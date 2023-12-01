<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Exceptions;

use LogicException;

final class InvalidFoundryProxyException extends LogicException
{
    private const CODE = 0013;

    public function __construct(string $message = null, mixed ...$args)
    {
        parent::__construct($message, self::CODE, ...$args);
    }
}
