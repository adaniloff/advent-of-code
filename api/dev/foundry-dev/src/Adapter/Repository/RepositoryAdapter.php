<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Adapter\Repository;

use App\Domain\Criteria\Criteria;
use App\Domain\Model\ModelInterface;

use function App\get_type_or_class;

use App\Infrastructure\Exceptions\NotImplementedInfrastructureException;
use App\Infrastructure\Storage\Symfony\Cache\InCacheRepositoryInterface;
use DateInterval;
use Dev\Foundry\App\Adapter\Entity\AbstractFoundryProxy;
use Dev\Foundry\App\Exceptions\UnsupportedModelException;
use Doctrine\Persistence\ObjectRepository;

/**
 * @implements ObjectRepository<ModelInterface>
 */
final class RepositoryAdapter implements InCacheRepositoryInterface, ObjectRepository
{
    /**
     * @psalm-param class-string<ModelInterface> $class
     */
    public function __construct(private readonly InCacheRepositoryInterface $parent, private readonly string $class) {}

    public function supports(mixed $object): bool
    {
        return $object instanceof AbstractFoundryProxy && $object->object() instanceof ModelInterface;
    }

    public function find(mixed $id): ?ModelInterface
    {
        return match (gettype($id)) {
            'string', 'object' => $this->findByUuid($id),
            'integer' => $this->findById($id),
            default => null,
        };
    }

    public function findAll(): iterable
    {
        return $this->listByCriteria(new Criteria());
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): iterable
    {
        throw new NotImplementedInfrastructureException();
    }

    public function findOneBy(array $criteria): ?ModelInterface
    {
        throw new NotImplementedInfrastructureException();
    }

    public function getClassName(): string
    {
        return $this->class;
    }

    public function findByUuid(mixed $uuid): ?ModelInterface
    {
        return $this->parent->findByUuid($uuid);
    }

    public function findById(mixed $id): ?ModelInterface
    {
        return $this->parent->findById($id);
    }

    /**
     * @return ModelInterface[]
     */
    public function listByCriteria(Criteria $criteria): array
    {
        return $this->parent->listByCriteria($criteria);
    }

    public function save(mixed $model): void
    {
        $this->supportsOrThrow($model);
        $this->parent->save($model->object());
    }

    public function delete(mixed $mixed, bool|DateInterval $expiration = null): void
    {
        $this->supportsOrThrow($mixed);
        $this->parent->delete($mixed->object(), false); // force the cache to be removed
    }

    public function empty(string $prefix = ''): void
    {
        $this->parent->empty($prefix);
    }

    public function clear(): void
    {
        $this->parent->clear();
    }

    /**
     * @param null[] $criteria
     */
    public function count(array $criteria = []): int
    {
        return $this->parent->count([]);
    }

    private function supportsOrThrow(mixed $entity): void
    {
        if (!$this->supports($entity)) {
            throw new UnsupportedModelException(sprintf('The entity must be an instance of `%s`, type `%s found`.', AbstractFoundryProxy::class, get_type_or_class($entity)));
        }
    }
}
