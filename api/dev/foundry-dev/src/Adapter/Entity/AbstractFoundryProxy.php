<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Adapter\Entity;

use App\Domain\Model\ModelInterface;
use Dev\Foundry\App\Adapter\Repository\RepositoryAdapter;
use Package\AyruuKit\App\Traits\Classes\AdapterTrait;

/**
 * @template TModel of ModelInterface
 *
 * @mixin TModel a ModelInterface class implementation.
 */
abstract class AbstractFoundryProxy
{
    use AdapterTrait;

    /**
     * @psalm-var TModel
     */
    private mixed $parent;

    /**
     * @psalm-param class-string<TModel> $class
     */
    public function __construct(string $class, private readonly RepositoryAdapter $store, mixed ...$attributes)
    {
        $entity = new $class(...$attributes);
        $this->parent = $entity;
    }

    public function empty(): void
    {
        $this->store->empty();
    }

    public function clear(): void
    {
        $this->store->clear();
    }

    /**
     * @psalm-return TModel
     *
     * @return TModel
     */
    public function object(): mixed
    {
        return $this->parent;
    }
}
