<?php

declare(strict_types=1);

namespace Dev\Ayruu\App;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @codeCoverageIgnore
 */
final class Reflection
{
    public static function getProperty(object $instance, string $property): mixed
    {
        $class = new ReflectionClass($instance);
        do {
            if ($class->hasProperty($property)) {
                $value = $class->getProperty($property)->getValue($instance);
            }
            $class = $class->getParentClass();
        } while ($class && !isset($value));

        return $value ?? null;
    }

    /**
     * todo: https://ayruu.atlassian.net/browse/AYR-1848.
     *
     * @throws ReflectionException
     */
    public static function setProperty(object $instance, string $property, mixed $value): void
    {
        (new ReflectionProperty($instance, $property))->setValue($instance, $value);
    }

    /**
     * todo: https://ayruu.atlassian.net/browse/AYR-1848.
     *
     * @throws ReflectionException
     */
    public static function callMethod(object $instance, string $method, mixed ...$args): mixed
    {
        return (new ReflectionMethod($instance, $method))->invoke($instance, ...$args);
    }
}
