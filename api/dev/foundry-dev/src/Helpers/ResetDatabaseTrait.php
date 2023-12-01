<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Helpers;

use Zenstruck\Foundry\Test\ResetDatabase as FoundryResetDatabase;

/**
 * Use this trait in order to support Foundry\ResetDatabase without coupling your concrete classes to it.
 */
trait ResetDatabaseTrait
{
    use FoundryResetDatabase {
        _resetSchema as resetSc;
        _resetDatabase as resetDb;
    }

    /**
     * @internal
     *
     * @beforeClass
     */
    public static function _resetDatabase(): void
    {
        self::resetDb();
    }

    /**
     * @internal
     *
     * @before
     */
    public static function _resetSchema(): void
    {
        self::resetSc();
    }
}
