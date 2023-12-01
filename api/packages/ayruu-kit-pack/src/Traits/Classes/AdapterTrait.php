<?php

declare(strict_types=1);

namespace Package\AyruuKit\App\Traits\Classes;

use Error;
use LogicException;
use Stringable;

/**
 * A trait to make the adapters automatically forward calls to parent classes.
 */
trait AdapterTrait
{
    public function __destruct()
    {
        $this->hasParent();

        if (method_exists($this->parent, '__destruct')) { // @phpstan-ignore-line
            $this->parent->__destruct();
        }
    }

    /**
     * @param array<mixed> $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        $this->hasParent();

        return $this->parent->{$name}(...$arguments);
    }

    /**
     * @param array<mixed> $arguments
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        if (!defined(static::class.'::PARENT')) {
            throw new LogicException(sprintf('Constant %s must be defined with the adapted class name definition.', 'PARENT'));
        }

        $adapted = constant(static::class.'::PARENT');

        return $adapted::$name(...$arguments);
    }

    public function __clone(): void
    {
        $this->hasParent();

        if (method_exists($this->parent, '__clone')) { // @phpstan-ignore-line
            $this->parent->__clone();
        }
    }

    public function __set(string $name, mixed $value): void
    {
        $this->hasParent();

        $this->parent->{$name} = $value;
    }

    public function __get(string $name): mixed
    {
        $this->hasParent();

        return $this->parent->{$name};
    }

    public function __isset(string $name): bool
    {
        $this->hasParent();

        return isset($this->parent->{$name});
    }

    public function __unset(string $name): void
    {
        $this->hasParent();

        unset($this->parent->{$name});
    }

    public function __toString(): string
    {
        $this->hasParent();

        if (method_exists($this->parent, '__toString') || $this->parent instanceof Stringable) { // @phpstan-ignore-line
            return (string) $this->parent;
        }
        // @phpstan-ignore-next-line
        throw new Error(sprintf('Cannot cast %s to string.', static::class));
    }

    //    /**
    //     * @throws \RuntimeException
    //     *
    //     * @return array<int, string>
    //     */
    //    public function __sleep(): array
    //    {
    //        throw new \RuntimeException(sprintf('Method %s is not implemented for now.', __METHOD__));
    //    }
    //
    //    public function __wakeup(): void
    //    {
    //        $this->hasParent();
    //
    //        if (method_exists($this->parent, '__wakeup')) { // @phpstan-ignore-line
    //            $this->parent->__wakeup();
    //        }
    //    }
    //
    //    /**
    //     * @throws \RuntimeException
    //     *
    //     * @return array<mixed>
    //     */
    //    public function __serialize(): array
    //    {
    //        throw new \RuntimeException(sprintf('Method %s is not implemented for now.', __METHOD__));
    //    }

    /**
     * @param array<mixed> $data
     */
    public function __unserialize(array $data): void
    {
        $this->hasParent();
        $this->parent = $data[sprintf("\x00%s\x00parent", static::class)];
    }

    public function __invoke(mixed ...$args): mixed
    {
        $this->hasParent();
        $parent = $this->parent;

        if (is_callable($parent)) { // @phpstan-ignore-line
            return $parent(...$args);
        }
        // @phpstan-ignore-next-line
        throw new Error(sprintf('Object of type %s is not callable', static::class));
    }

    private function hasParent(): void
    {
        if (!property_exists($this, 'parent')) {
            throw new LogicException(sprintf('Protected property %s must be defined with the adapted object.', 'parent'));
        }
    }
}
