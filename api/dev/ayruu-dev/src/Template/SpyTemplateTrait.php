<?php

declare(strict_types=1);

namespace Dev\Ayruu\App\Template;

/**
 * A trait use to easily set-up spies.
 */
trait SpyTemplateTrait
{
    /**
     * @var array<string, int>
     */
    private static array $calls;

    /**
     * @phpstan-ignore-next-line
     *
     * @todo: fix phpstan issue
     */
    private static array $args;

    public static function get(string $name): ?int
    {
        return self::$calls[$name] ?? null;
    }

    public static function args(string $name, int $call = 1, int $index = null): mixed
    {
        if (null !== $index) {
            return self::$args[$name][$call][$index] ?? null;
        }

        return self::$args[$name][$call] ?? null;
    }

    public static function reset(): void
    {
        self::$calls = [];
        self::$args = [];
    }

    protected function spy(mixed ...$args): void
    {
        $trace = debug_backtrace();
        $previous = $trace[1]['function'];

        if (!isset(self::$calls[$previous])) {
            self::$calls[$previous] = 0;
        }

        ++self::$calls[$previous];

        if (!empty($args)) {
            self::$args[$previous][self::$calls[$previous]] = $args;
        }
    }
}
