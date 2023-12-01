<?php

declare(strict_types=1);

namespace Dev\Ayruu\App\Transformer;

use App\Domain\Criteria\{Criteria, Filter, Parameter};
use App\Infrastructure\Storage\QueryTransformerInterface;

interface InMemoryQueryTransformerInterface extends QueryTransformerInterface
{
    public function criteria(Criteria $criteria): callable;

    public function filter(Filter $filter): callable;

    public function parameter(Parameter $parameter): callable;

    /**
     * @return array<callable>
     */
    public function parameters(Criteria $criteria): array;
}
