<?php

declare(strict_types=1);

namespace Package\AyruuKit\App\Traits\Classes;

use Psr\Log\{LoggerInterface, NullLogger};

/**
 * A trait to apply a logging policy.
 */
trait LogAwareTrait
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->setLogger($logger);
    }

    /**
     * Forward any unknown call to the logger.
     *
     * @param array<mixed> $arguments
     */
    public function __call(string $name, array $arguments): void
    {
        $this->logger->info(sprintf('%s::%s has been called, but the method does not exist.', __CLASS__, $name));
    }

    /**
     * @codeCoverageIgnore
     */
    public function setLogger(LoggerInterface $logger = null): void
    {
        $this->logger = $logger ?? new NullLogger();
    }
}
