<?php

declare(strict_types=1);

namespace Dev\Ayruu\Tests\Transformer;

use App\Domain\Criteria\{Criteria, Filter, Parameter, Sort};
use App\Domain\Model\ModelInterface;
use App\Infrastructure\Uuid\Symfony\Uuid;
use Closure;
use DateTime;
use DateTimeInterface;
use Dev\Ayruu\App\Transformer\InMemoryQueryTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @group internal
 *
 * @coversDefaultClass \Dev\Ayruu\App\Transformer\InMemoryQueryTransformer
 *
 * @internal
 */
final class InMemoryQueryTransformerTest extends TestCase
{
    private InMemoryQueryTransformer $transformer;

    public function setUp(): void
    {
        parent::setUp();

        $this->transformer = new InMemoryQueryTransformer();
    }

    /**
     * @covers ::criteria
     */
    public function testWhereCriteriaNestedWithORAND(): void
    {
        // Arrange
        $fixtures = $this->fixtures()[__FUNCTION__];

        $expected = [
            $fixtures['ayruu-julie-tan'],
            $fixtures['ayruu-julie-doe'],
            $fixtures['ayruu-admin'],
        ];

        $isJulieUserExpr = Filter::and(
            Filter::eq('firstname', 'julie'),
            Filter::eq('role', 'ROLE_USER'),
        );
        $isJulieUserOrAdminExpr = Filter::or(
            Filter::eq('role', 'ROLE_ADMIN'),
            $isJulieUserExpr,
        );

        $ayruuJulieOrAyruuAdminExpr = (new Criteria())
            ->add($isJulieUserOrAdminExpr)
            ->add(Filter::eq('company.name', 'AYRUU'))
        ;

        // Act
        $result = $this->transformer->criteria($ayruuJulieOrAyruuAdminExpr);

        // Assert
        $this->assertInstanceOf(Closure::class, $result);
        $this->assertEquals($expected, $result($fixtures));
    }

    /**
     * @covers ::criteria
     */
    public function testComparisonCriteria(): void
    {
        // Arrange
        $fixtures = $this->fixtures()[__FUNCTION__];
        $expected = [
            $fixtures['sputnik-corporate'],
        ];

        $isCorporateExpr = Filter::in('role', ['ROLE_CORPORATE', 'ROLE_CORPORATE_ADMIN']);
        $excludeDangerousExpr = Filter::notIn('company.name', ['JOHNSON', 'MODERNA']);
        $highRateBoosterExpr = Filter::gt('company.booster', 75);
        $notPopularExpr = Filter::lte('company.popularity.value', 50);

        $safeUnderestimatedCorporateVaccinesExpr = (new Criteria())
            ->add($isCorporateExpr)
            ->add($excludeDangerousExpr)
            ->add($highRateBoosterExpr)
            ->add($notPopularExpr)
        ;

        // Act
        $result = $this->transformer->criteria($safeUnderestimatedCorporateVaccinesExpr);

        // Assert
        $this->assertInstanceOf(Closure::class, $result);
        $this->assertEquals($expected, $result($fixtures));
    }

    /**
     * @covers ::sort
     */
    public function testSortingCriteria(): void
    {
        // Arrange
        $fixtures = $this->fixtures()[__FUNCTION__];
        $expected = [
            $fixtures['sputnik-corporate'],
            $fixtures['moderna-admin'],
            $fixtures['pfizer-corporate'],
        ];

        $highRateBoosterExpr = Filter::gt('company.booster', 75);
        $vaccinesByPopularityExpr = (new Criteria())
            ->add($highRateBoosterExpr)
            ->add(new Sort('company.popularity.value', 'ASC'))
        ;

        // Act
        $result = $this->transformer->criteria($vaccinesByPopularityExpr);

        // Assert
        $this->assertInstanceOf(Closure::class, $result);
        $this->assertEquals(array_values($expected), array_values($result($fixtures)));
    }

    /**
     * @covers ::sort
     */
    public function testMultiSortingCriteria(): void
    {
        // Arrange
        $fixtures = $this->fixtures()[__FUNCTION__];
        $expected = [
            $fixtures['moderna-admin'],
            $fixtures['sputnik-corporate'],
            $fixtures['pfizer-corporate'],
        ];

        $highRateBoosterExpr = Filter::gt('company.booster', 75);
        $vaccinesByAgeAndBoosterExpr = (new Criteria())
            ->add($highRateBoosterExpr)
            ->add(new Sort('company.createdAt', 'DESC'))
            ->add(new Sort('company.booster', 'ASC'))
        ;

        // Act
        $result = $this->transformer->criteria($vaccinesByAgeAndBoosterExpr);

        // Assert
        $this->assertInstanceOf(Closure::class, $result);
        $this->assertEquals(array_values($expected), array_values($result($fixtures)));
    }

    /**
     * @covers ::filter
     * @covers ::filterLogical
     */
    public function testCriteriaWithNestedLogicalFilter(): void
    {
        // Arrange
        $fixtures = $this->fixtures()[__FUNCTION__];
        $expected = [
            $fixtures['vlc'],
        ];

        $isCompanyOldAndSmallAndCaliforniaBased = Filter::and(
            Filter::lt('company.founded', 2000),
            Filter::lte('company.size', 15),
            Filter::in('company.location', ['SanFrancisco', 'SanJose', 'LosAngeles']),
        );

        $isCompanyATechCompany = Filter::or(
            Filter::in('company.market', ['computer', 'design', 'phone']),
            Filter::and(
                Filter::neq('company.sold', true),
                Filter::eq('company.buyerStatus', 'tech'),
            ),
        );

        // Act
        $result = $this->transformer->filter(Filter::and(
            $isCompanyOldAndSmallAndCaliforniaBased,
            $isCompanyATechCompany,
        ));

        // Assert
        $this->assertInstanceOf(Closure::class, $result);
        $this->assertEquals($expected, $result($fixtures));
    }

    /**
     * @covers ::parameter
     */
    public function testParameter(): void
    {
        $this->markTestSkipped();
    }

    /**
     * @covers ::parameters
     */
    public function testCriteriaWithParameters(): void
    {
        $transformer = new InMemoryQueryTransformer();

        $parameters = $transformer->parameters(
            (new Criteria())
                ->add(new Parameter('ra', 'gondin'))
                ->add(new Parameter('tarte', 'tatin'))
        );

        self::assertCount(2, $parameters);
    }

    /**
     * @return array<string, array<string, AbstractUser>>
     */
    private function fixtures(): array
    {
        return [
            'testWhereCriteriaNestedWithORAND' => [
                'ayruu-julie-tan' => AyruuUser::user(firstname: 'julie', lastname: 'tan'),
                'ayruu-julie-doe' => AyruuUser::user(firstname: 'julie', lastname: 'doe'),
                'ayruu-admin' => AyruuUser::admin(firstname: 'julie', lastname: 'tan'),
                'ayruu-julie-doe-corporate' => AyruuUser::corporate(firstname: 'julie', lastname: 'doe'),
                'trigone-julie-doe' => TrigoneUser::user(firstname: 'julie', lastname: 'doe'),
                'trigone-admin' => TrigoneUser::admin(),
            ],
            'testComparisonCriteria' => [
                'moderna-admin' => ModernaUser::admin(),
                'sputnik-corporate' => SputnikUser::corporate(),
                'pfizer-corporate' => PfizerUser::corporate(),
            ],
            'testSortingCriteria' => [
                'moderna-admin' => ModernaUser::admin(),
                'sputnik-corporate' => SputnikUser::corporate(),
                'pfizer-corporate' => PfizerUser::corporate(),
            ],
            'testMultiSortingCriteria' => [
                'moderna-admin' => ModernaUser::admin(),
                'sputnik-corporate' => SputnikUser::corporate(),
                'pfizer-corporate' => PfizerUser::corporate(),
            ],
            'testCriteriaWithNestedLogicalFilter' => [
                'google' => GoogleUser::user(),
                'apple' => AppleUser::user(),
                'ayruu' => AyruuUser::user(),
                'trigone-tech' => TrigoneUser::user(),
                'vlc' => VLCUser::user(),
            ],
        ];
    }
}

abstract class AbstractUser implements ModelInterface
{
    public function __construct(
        protected AbstractCompany $company,
        protected string $firstname,
        protected string $lastname,
        protected string $role,
        protected ?int $id = null,
        protected null|Uuid $uuid = null,
    ) {
        $this->id ??= random_int(10000, 99999);
        $this->uuid ??= Uuid::createWithUuid();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function getCompany(): AbstractCompany
    {
        return $this->company;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public static function user(mixed ...$args): static
    {
        return static::create(...[...$args, 'role' => 'ROLE_USER']);
    }

    public static function admin(mixed ...$args): static
    {
        return static::create(...[...$args, 'role' => 'ROLE_ADMIN']);
    }

    public static function corporate(mixed ...$args): static
    {
        return static::create(...[...$args, 'role' => 'ROLE_CORPORATE']);
    }

    public static function corporateAdmin(mixed ...$args): static
    {
        return static::create(...[...$args, 'role' => 'ROLE_CORPORATE_ADMIN']);
    }

    abstract public static function create(mixed ...$args): static;
}

abstract class AbstractCompany implements ModelInterface
{
    public function __construct(
        protected string $name,
        protected ?int $id = null,
        protected ?Uuid $uuid = null,
        protected ?DateTimeInterface $createdAt = new DateTime(),
    ) {
        $this->id ??= random_int(10000, 99999);
        $this->uuid ??= Uuid::createWithUuid();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}

final class Popularity
{
    public function __construct(private int $value) {}

    public function getValue(): int
    {
        return $this->value;
    }
}

final class VaccineCompany extends AbstractCompany
{
    public function __construct(private int $booster, private Popularity $popularity, mixed ...$args)
    {
        parent::__construct($args['name'], $args['id'] ?? null, $args['uuid'] ?? null, $args['createdAt'] ?? null);
    }

    public function getBooster(): int
    {
        return $this->booster;
    }

    public function getPopularity(): Popularity
    {
        return $this->popularity;
    }
}

final class BusinessCompany extends AbstractCompany
{
    public function __construct(private int $founded, private int $size, private string $location, private string $market, private bool $sold = false, private ?string $buyerStatus = null, mixed ...$args)
    {
        parent::__construct($args['name'], $args['id'] ?? null, $args['uuid'] ?? null, $args['createdAt'] ?? null);
    }

    public function getFounded(): int
    {
        return $this->founded;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getMarket(): string
    {
        return $this->market;
    }

    public function isSold(): bool
    {
        return $this->sold;
    }

    public function getBuyerStatus(): string
    {
        return $this->buyerStatus;
    }
}

final class AyruuUser extends AbstractUser
{
    public static function create(mixed ...$args): static
    {
        $args['company'] ??= new BusinessCompany(founded: 2018, size: 15, location: 'Paris', market: 'travel', name: 'AYRUU');
        $args['firstname'] ??= 'aleksandr';
        $args['lastname'] ??= 'daniloff';

        return new self(...$args);
    }
}

final class TrigoneUser extends AbstractUser
{
    public static function create(mixed ...$args): static
    {
        $args['company'] ??= new BusinessCompany(founded: 2015, size: 2, location: 'Paris', market: 'design', name: 'TRIGONE_TECH');
        $args['firstname'] ??= 'nicolas';
        $args['lastname'] ??= 'feuillade';

        return new self(...$args);
    }
}

final class PfizerUser extends AbstractUser
{
    public static function create(mixed ...$args): static
    {
        $args['company'] ??= new VaccineCompany(booster: 90, popularity: new Popularity(99), name: 'PFIZER', createdAt: new DateTime('2020-10-10'));
        $args['firstname'] ??= 'john';
        $args['lastname'] ??= 'doe';

        return new self(...$args);
    }
}

final class ModernaUser extends AbstractUser
{
    public static function create(mixed ...$args): static
    {
        $args['company'] ??= new VaccineCompany(booster: 85, popularity: new Popularity(60), name: 'MODERNA', createdAt: new DateTime('2021-01-15'));
        $args['firstname'] ??= 'john';
        $args['lastname'] ??= 'doe';

        return new self(...$args);
    }
}

final class SputnikUser extends AbstractUser
{
    public static function create(mixed ...$args): static
    {
        $args['company'] ??= new VaccineCompany(booster: 95, popularity: new Popularity(30), name: 'SPUTNIK', createdAt: new DateTime('2020-10-10'));
        $args['firstname'] ??= 'john';
        $args['lastname'] ??= 'doe';

        return new self(...$args);
    }
}

final class GoogleUser extends AbstractUser
{
    public static function create(mixed ...$args): static
    {
        $args['company'] ??= new BusinessCompany(founded: 1999, size: 2000, location: 'SanFrancisco', market: 'computer', sold: true, name: 'Google');
        $args['firstname'] ??= 'john';
        $args['lastname'] ??= 'doe';

        return new self(...$args);
    }
}

final class AppleUser extends AbstractUser
{
    public static function create(mixed ...$args): static
    {
        $args['company'] ??= new BusinessCompany(founded: 1985, size: 1900, location: 'LosAngeles', market: 'fruits', buyerStatus: 'tech', name: 'Apple');
        $args['firstname'] ??= 'john';
        $args['lastname'] ??= 'doe';

        return new self(...$args);
    }
}

final class VLCUser extends AbstractUser
{
    public static function create(mixed ...$args): static
    {
        $args['company'] ??= new BusinessCompany(founded: 1999, size: 10, location: 'SanFrancisco', market: 'computer', sold: true, name: 'VLC');
        $args['firstname'] ??= 'john';
        $args['lastname'] ??= 'doe';

        return new self(...$args);
    }
}
