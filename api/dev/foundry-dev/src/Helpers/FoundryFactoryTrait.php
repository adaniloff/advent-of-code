<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Helpers;

use App\Domain\Model\ModelInterface;
use App\Infrastructure\Storage\Symfony\Cache\InCacheRepositoryInterface;
use Dev\Foundry\App\Adapter\Entity\{AbstractFoundryProxy};
use Dev\Foundry\App\Adapter\Repository\{ChainManagerRegistryAdapter, RepositoryAdapter};
use Dev\Foundry\App\Exceptions\InvalidFoundryProxyException;
use Doctrine\Persistence\ManagerRegistry;
use Zenstruck\Foundry\{ModelFactory, Proxy};

trait FoundryFactoryTrait
{
    abstract public static function getClass(): string;

    abstract public function getRepository(): InCacheRepositoryInterface;

    abstract public function getRegistry(): ManagerRegistry;

    /**
     * Remove all elements from entity relative's cache.
     */
    public static function empty(): void
    {
        $initializer = self::warmup();
        $initializer->empty();
    }

    /**
     * Remove all elements from all cache.
     */
    public static function clear(): void
    {
        $initializer = self::warmup();
        $initializer->clear();
    }

    /**
     * @see ModelFactory::initialize()
     */
    protected function initialize(): self
    {
        // create a custom caching repository adapter.
        $cache = new RepositoryAdapter($this->getRepository(), self::getClass());
        // replace the doctrine persistence with the caching one.
        self::configuration()->setManagerRegistry(new ChainManagerRegistryAdapter($this->getRegistry(), $cache));

        return $this
            ->instantiateWith(function (array $attributes) use ($cache): object {
                // create a caching proxy of our entity.
                $proxyCacheName = ChainManagerRegistryAdapter::mapping(self::getClass());

                return new $proxyCacheName($cache, ...$attributes);
            })
        ;
    }

    /**
     * Warmup the factory by creating an instance of an object.
     *
     * @return AbstractFoundryProxy<ModelInterface>
     *
     *@see FoundryFactoryTrait::initialize()
     */
    private static function warmup(): AbstractFoundryProxy
    {
        $initializer = self::new()->withoutPersisting()->create();
        if ($initializer instanceof Proxy) {
            $initializer = $initializer->object();
        }
        if (!$initializer instanceof AbstractFoundryProxy) {
            throw new InvalidFoundryProxyException(sprintf('%s proxies must be subproxified with %s.', self::class, AbstractFoundryProxy::class));
        }

        return $initializer;
    }
}
