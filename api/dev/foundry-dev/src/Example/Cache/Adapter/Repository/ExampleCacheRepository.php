<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Example\Cache\Adapter\Repository;

use App\Domain\Criteria\Criteria;
use App\Domain\Model\{ModelInterface, UuidInterface};
use App\Infrastructure\Storage\Symfony\Cache\InCacheRepositoryInterface;
use App\Infrastructure\Storage\Symfony\Cache\{InCacheRepository};
use DateInterval;
use Dev\Foundry\App\Example\Cache\Adapter\Entity\Example;
use Dev\Foundry\App\Exceptions\UnsupportedModelException;
use Package\AyruuKit\App\Traits\Classes\AdapterTrait;

final class ExampleCacheRepository implements InCacheRepositoryInterface
{
    use AdapterTrait;

    public const INDEX = 'examples';
    public const PARENT = InCacheRepository::class;

    public function __construct(private InCacheRepository $parent)
    {
        $this->parent->setIndex(self::INDEX);
    }

    public function supports(mixed $object): bool
    {
        return $object instanceof Example;
    }

    public function findById(int $id): ?Example
    {
        /**
         * @var Example|null $request - bc of phpstan issue: $this->supports() type detection is broken
         */
        $request = $this->parent->findById($id);
        if (!$this->supports($request)) {
            $request = null;
        }

        return $request;
    }

    public function findByUuid(string $uuid): ?Example
    {
        /**
         * @var Example|null $request - bc of phpstan issue: $this->supports() type detection is broken
         */
        $request = $this->parent->findByUuid($uuid);
        if (!$this->supports($request)) {
            $request = null;
        }

        return $request;
    }

    public function listByCriteria(Criteria $criteria): array
    {
        return $this->parent->listByCriteria($criteria);
    }

    public function save(Example|ModelInterface $model): void
    {
        $this->supportsOrThrow($model);
        $this->parent->save($model);
    }

    public function delete(Example|int|ModelInterface|string|UuidInterface $mixed, bool|DateInterval $expiration = true): void
    {
        if ($mixed instanceof ModelInterface && !$this->supports($mixed)) {
            throw new UnsupportedModelException(sprintf('Argument `%s` must be an instanceof `%s`.', 'mixed', Example::class));
        }

        $this->parent->delete($mixed, $expiration);
    }

    /**
     * @param null[] $criteria
     */
    public function count(array $criteria = []): int
    {
        return $this->parent->count();
    }

    public function empty(string $prefix = ''): void
    {
        $this->parent->empty(self::INDEX);
    }

    public function clear(): void
    {
        $this->parent->clear();
    }

    private function supportsOrThrow(mixed $entity): void
    {
        if (!$this->supports($entity)) {
            throw new UnsupportedModelException(sprintf('The entity must be an instance of `%s`.', Example::class));
        }
    }
}
