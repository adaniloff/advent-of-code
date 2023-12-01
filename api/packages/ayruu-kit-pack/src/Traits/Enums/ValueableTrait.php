<?php

declare(strict_types=1);

namespace Package\AyruuKit\App\Traits\Enums;

use BackedEnum;
use LogicException;

/**
 * A trait to add default methods to our enums.
 */
trait ValueableTrait
{
    /**
     * Return all values from a BackedEnum.
     *
     * @return array<mixed>
     */
    public static function values(): array
    {
        if (!is_subclass_of(static::class, BackedEnum::class)) { // @phpstan-ignore-line - not supported by phpstan yet > https://github.com/phpstan/phpstan/issues/6697
            throw new LogicException(sprintf('The `%s` trait can only be used on `%s`.', __TRAIT__, BackedEnum::class));
        }

        // @phpstan-ignore-next-line > https://github.com/phpstan/phpstan/issues/6697
        return array_map(fn (BackedEnum $enum): mixed => $enum->value, static::cases());
    }
}
