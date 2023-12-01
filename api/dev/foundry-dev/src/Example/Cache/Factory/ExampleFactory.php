<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Example\Cache\Factory;

use App\Infrastructure\Storage\Symfony\Cache\InCacheRepositoryInterface;
use Dev\Foundry\App\Example\Cache\Adapter\Entity\Example;
use Dev\Foundry\App\Example\Cache\Adapter\Entity\{ExampleCacheProxy};
use Dev\Foundry\App\Example\Cache\Adapter\Repository\ExampleCacheRepository;
use Dev\Foundry\App\Helpers\FoundryFactoryTrait;
use Doctrine\Persistence\ManagerRegistry;
use Zenstruck\Foundry\{ModelFactory, Proxy};

/**
 * @extends ModelFactory<Example>
 *
 * @method static ExampleCacheProxy       createOne(array $attributes = [])
 * @method static ExampleCacheProxy[]     createMany(int $number, array|callable $attributes = [])
 * @method static ExampleCacheProxy       find(object|array|mixed $criteria)
 * @method static ExampleCacheProxy       findOrCreate(array $attributes)
 * @method static ExampleCacheProxy       first(string $sortedField = 'id')
 * @method static ExampleCacheProxy       last(string $sortedField = 'id')
 * @method static ExampleCacheProxy       random(array $attributes = [])
 * @method static ExampleCacheProxy       randomOrCreate(array $attributes = [])
 * @method static ExampleCacheProxy[]     all()
 * @method static ExampleCacheProxy[]     findBy(array $attributes)
 * @method static ExampleCacheProxy[]     randomSet(int $number, array $attributes = [])
 * @method static ExampleCacheProxy[]     randomRange(int $min, int $max, array $attributes = [])
 * @method        ExampleCacheProxy|Proxy create(array|callable $attributes = [])
 */
final class ExampleFactory extends ModelFactory
{
    use FoundryFactoryTrait;

    /**
     * @param ExampleCacheRepository&InCacheRepositoryInterface $repository
     */
    public function __construct(private readonly ExampleCacheRepository $repository, private readonly ManagerRegistry $registry)
    {
        parent::__construct();
    }

    public static function getClass(): string
    {
        return Example::class;
    }

    public function getRepository(): InCacheRepositoryInterface
    {
        return $this->repository;
    }

    public function getRegistry(): ManagerRegistry
    {
        return $this->registry;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        return [];
    }
}
