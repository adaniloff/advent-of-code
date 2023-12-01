<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Factory;

use App\Infrastructure\Storage\Doctrine\Entity\User;
use App\Infrastructure\Storage\Doctrine\Repository\UserRepository;
use App\Infrastructure\Uuid\Symfony\Uuid;
use Zenstruck\Foundry\{ModelFactory, Proxy, RepositoryProxy};

/**
 * @extends ModelFactory<User>
 *
 * @method        Proxy<User>                     create(array|callable $attributes = [])
 * @method static Proxy<User>                     createOne(array $attributes = [])
 * @method static Proxy<User>                     find(object|array|mixed $criteria)
 * @method static Proxy<User>                     findOrCreate(array $attributes)
 * @method static Proxy<User>                     first(string $sortedField = 'id')
 * @method static Proxy<User>                     last(string $sortedField = 'id')
 * @method static Proxy<User>                     random(array $attributes = [])
 * @method static Proxy<User>                     randomOrCreate(array $attributes = [])
 * @method static RepositoryProxy<UserRepository> repository()
 * @method static Proxy<User>[]                   all()
 * @method static Proxy<User>[]                   createMany(int $number, array|callable $attributes = [])
 * @method static Proxy<User>[]                   createSequence(iterable|callable $sequence)
 * @method static Proxy<User>[]                   findBy(array $attributes)
 * @method static Proxy<User>[]                   randomRange(int $min, int $max, array $attributes = [])
 * @method static Proxy<User>[]                   randomSet(int $number, array $attributes = [])
 */
final class UserFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        return [
            'email' => self::faker()->text(180),
            'password' => self::faker()->text(),
            'roles' => [],
            'uuid' => Uuid::createWithUuid(), // TODO add AYRUU_UUID type manually
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this;
        // ->afterInstantiate(function(User $user): void {})
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
