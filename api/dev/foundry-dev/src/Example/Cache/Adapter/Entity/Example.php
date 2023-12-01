<?php

declare(strict_types=1);

namespace Dev\Foundry\App\Example\Cache\Adapter\Entity;

use App\Domain\Model\ModelInterface;
use App\Infrastructure\Uuid\Symfony\Uuid;

use function Zenstruck\Foundry\faker;

class Example implements ModelInterface
{
    private ?Uuid $uuid = null;

    public function __construct(private ?string $name = null)
    {
        $this->name ??= faker()->name();
        $this->uuid ??= Uuid::createWithUuid();
    }

    /**
     * @codeCoverageIgnore
     */
    public function getId(): ?int
    {
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
