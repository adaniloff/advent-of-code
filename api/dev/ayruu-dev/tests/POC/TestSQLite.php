<?php

declare(strict_types=1);

namespace Dev\Ayruu\Tests\POC;

use App\Infrastructure\Storage\Doctrine\Repository\UserRepository;
use Dev\Foundry\App\Factory\UserFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 *
 * @coversNothing
 */
final class TestSQLite extends KernelTestCase
{
    use Factories;
    private const ITERATIONS = 500;

    protected function setUp(): void
    {
        /** @var EntityManager $entityManager */
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $metaData = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metaData);
    }

    public function testAllPersistFlush(): void
    {
        UserFactory::createMany(self::ITERATIONS);
        self::assertCount(self::ITERATIONS, UserFactory::all());
        $user = UserFactory::all()[0];
        $users = UserFactory::findBy(['uuid' => $user->getUuid()]);
        self::assertCount(1, $users);
        self::assertEquals($user, $users[0]);
    }

    public function testOneFlush(): void
    {
        /** @var UserRepository $repository */
        $repository = self::getContainer()->get(UserRepository::class);
        for ($i = 0; $i < self::ITERATIONS; ++$i) {
            $user = UserFactory::new()->withoutPersisting()->create();
            $repository->add($user->object());
        }
        $repository->flush();
        self::assertCount(self::ITERATIONS, $repository->findAll());
        $user = $repository->findAll()[0];
        $users = UserFactory::findBy(['uuid' => $user->getUuid()]);
        self::assertCount(1, $users);
        self::assertEquals($user, $users[0]->object());
    }
}
