<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Exceptions;

use LogicException;

final class NotImplementedFoundryException extends LogicException
{
    private const CODE = 0011;

    public function __construct(string $message = null, mixed ...$args)
    {
        parent::__construct(sprintf('Not implemented yet%s.', $message ? " (reason: {$message})" : ''), self::CODE, ...$args);
    }
}
