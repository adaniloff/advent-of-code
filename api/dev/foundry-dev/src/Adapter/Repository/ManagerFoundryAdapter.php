<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Adapter\Repository;

use App\Domain\Model\{ModelInterface, UuidInterface};
use Dev\Foundry\App\Adapter\Entity\AbstractFoundryProxy;
use Dev\Foundry\App\Exceptions\NotImplementedFoundryException;
use Doctrine\ORM\Mapping\{ClassMetadata as DoctrineClassMetadata, ClassMetadataInfo};
use Doctrine\Persistence\Mapping\{ClassMetadata, ClassMetadataFactory};
use Doctrine\Persistence\ObjectManager;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionProperty;

final class ManagerFoundryAdapter implements ObjectManager
{
    public function __construct(private readonly RepositoryAdapter $repository) {}

    public function getRepository(mixed $className): RepositoryAdapter
    {
        return $this->repository;
    }

    public function find(mixed $className, mixed $id): ?ModelInterface
    {
        return $this->repository->find($id);
    }

    public function remove(mixed $object): void
    {
        $this->repository->delete($object);
    }

    public function clear(mixed $objectName = null): void
    {
        $this->repository->clear();
    }

    /**
     * @param AbstractFoundryProxy<ModelInterface> $object
     */
    public function persist(mixed $object): void
    {
        if (!$this->supports($object)) {
            throw new InvalidArgumentException(sprintf('Unsupported object argument in %s', __METHOD__));
        }

        $this->repository->save($object);
    }

    /**
     * @param AbstractFoundryProxy<ModelInterface> $object
     */
    public function contains(mixed $object): bool
    {
        return $this->supports($object) && null !== $this->repository->findByUuid($object->getUuid()->getUuid());
    }

    /**
     * @return ClassMetadata<object>
     */
    public function getClassMetadata(mixed $className): ClassMetadata
    {
        return $this->getMetadataFactory()->getMetadataFor($className);
    }

    public function merge(mixed $object): object
    {
        return $object;
    }

    public function detach(mixed $object): void
    {
        // no-op
    }

    public function refresh(mixed $object): void
    {
        // no-op
    }

    public function flush(): void
    {
        // no-op
    }

    public function initializeObject(object $obj): void
    {
        // no-op
    }

    /**
     * @return ClassMetadataFactory<ClassMetadata<object>>
     */
    public function getMetadataFactory(): ClassMetadataFactory
    {
        // @phpstan-ignore-next-line - ignoring bc of anonymous class detection issues
        return new class() implements ClassMetadataFactory {
            /**
             * @return array<ClassMetadata<ModelInterface>>
             */
            public function getAllMetadata(): array
            {
                throw $this->notAvailableException(__METHOD__);
            }

            /**
             * @param class-string $className
             *
             * @return ClassMetadata<ModelInterface>
             */
            public function getMetadataFor(mixed $className): ClassMetadata
            {
                /**
                 * @var DoctrineClassMetadata<ModelInterface> $metadata
                 */
                $metadata = new DoctrineClassMetadata($className);
                $metadata->reflClass = new ReflectionClass($className);

                /**
                 * Return the UUID value for a proxified object.
                 *
                 * @var ReflectionProperty $getter
                 *
                 * @see ClassMetadataInfo::getIdentifierValues()
                 */
                $getter = new class() {
                    public function getValue(mixed $object): ?UuidInterface
                    {
                        return $object instanceof AbstractFoundryProxy ? $object->object()->getUuid() : ($object instanceof ModelInterface ? $object->getUuid() : null);
                    }
                };
                $metadata->reflFields = ['uuid' => $getter];
                $metadata->setIdentifier(['uuid']);

                return $metadata;
            }

            /**
             * @param class-string $className
             */
            public function hasMetadataFor(mixed $className): bool
            {
                return (new ReflectionClass($className))->hasProperty('uuid');
            }

            /**
             * @param class-string                  $className
             * @param ClassMetadata<ModelInterface> $class
             */
            public function setMetadataFor(mixed $className, mixed $class): void
            {
                throw $this->notAvailableException(__METHOD__);
            }

            public function isTransient(mixed $className): bool
            {
                throw $this->notAvailableException(__METHOD__);
            }

            private function notAvailableException(string $method): NotImplementedFoundryException
            {
                return new NotImplementedFoundryException(sprintf('not available: %s', $method));
            }
        };
    }

    private function supports(object $object): bool
    {
        return $object instanceof ModelInterface || ($object instanceof AbstractFoundryProxy && $object->object() instanceof ModelInterface);
    }
}
