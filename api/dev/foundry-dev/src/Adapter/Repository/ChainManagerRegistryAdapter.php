<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Adapter\Repository;

use Dev\Foundry\App\Example\Cache\Adapter\Entity\Example;
use Dev\Foundry\App\Example\Cache\Adapter\Entity\{ExampleCacheProxy};
use Dev\Foundry\App\Exceptions\NotImplementedFoundryException;
use Doctrine\Persistence\{ManagerRegistry, ObjectManager, ObjectRepository};

/**
 * This manager's aim is to evaluate if a repository is a cached one or a doctrine one.
 */
final class ChainManagerRegistryAdapter implements ManagerRegistry
{
    /**
     * @var string[]
     */
    private static array $mappings = [
        Example::class => ExampleCacheProxy::class,
    ];

    public function __construct(private readonly ManagerRegistry $parent, private readonly RepositoryAdapter $repository) {}

    public function getRepository(mixed $persistentObject = null, mixed $persistentManagerName = null): ObjectRepository
    {
        if ($this->isCustomRepository($persistentObject)) {
            $repository = $this->repository;
        }

        $repository ??= $this->parent->getRepository($persistentObject, $persistentManagerName);

        return $repository; // @phpstan-ignore-line - ignoring bc of psalm detection issues
    }

    public function getDefaultConnectionName(): string
    {
        throw $this->notAvailableException(__METHOD__);
    }

    public function getConnection($name = null): object
    {
        throw $this->notAvailableException(__METHOD__);
    }

    /**
     * @return array<object>
     */
    public function getConnections(): array
    {
        throw $this->notAvailableException(__METHOD__);
    }

    /**
     * @return array<string>
     */
    public function getConnectionNames(): array
    {
        throw $this->notAvailableException(__METHOD__);
    }

    public function getDefaultManagerName(): string
    {
        throw $this->notAvailableException(__METHOD__);
    }

    public function getDefaultManager(): ManagerFoundryAdapter
    {
        return new ManagerFoundryAdapter($this->repository);
    }

    public function getManager($name = null): ?ObjectManager
    {
        return $this->getDefaultManager();
    }

    /**
     * @return array<ObjectManager>
     */
    public function getManagers(): array
    {
        throw $this->notAvailableException(__METHOD__);
    }

    public function resetManager($name = null): ?ObjectManager
    {
        throw $this->notAvailableException(__METHOD__);
    }

    /**
     * @return array<string>
     */
    public function getManagerNames(): array
    {
        throw $this->notAvailableException(__METHOD__);
    }

    public function getManagerForClass($class): ?ObjectManager
    {
        if ($this->isCustomRepository($class)) {
            return $this->getDefaultManager();
        }

        return $this->parent->getManagerForClass($class);
    }

    public static function mapping(string $className): ?string
    {
        return self::$mappings[$className];
    }

    private function isCustomRepository(mixed $persistentObject): bool
    {
        if (is_null($persistentObject)) {
            return true;
        }

        foreach (self::$mappings as $class => $proxy) {
            if (in_array($persistentObject, [$class, $proxy])) {
                return true;
            }
        }

        return false;
    }

    private function notAvailableException(string $method): NotImplementedFoundryException
    {
        return new NotImplementedFoundryException(sprintf('not available: %s', $method));
    }
}
