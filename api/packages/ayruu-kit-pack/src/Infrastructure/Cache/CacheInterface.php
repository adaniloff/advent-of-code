<?php

declare(strict_types=1);

namespace Package\AyruuKit\App\Infrastructure\Cache;

use Psr\Log\LoggerAwareInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\ResettableInterface;
use Symfony\Contracts\Cache\CacheInterface as SymfonyCacheInterface;

interface CacheInterface extends AdapterInterface, SymfonyCacheInterface, LoggerAwareInterface, ResettableInterface {}
