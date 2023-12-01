<?php

declare(strict_types=1);

namespace Dev\Ayruu\App\Transformer;

use App\Domain\Criteria\{Criteria, Filter, Parameter, Sort};
use Dev\Ayruu\App\Exceptions\NotImplementedDevException;
use ReflectionProperty;

final class InMemoryQueryTransformer implements InMemoryQueryTransformerInterface
{
    /**
     * @var array<int, Sort>
     */
    private array $sorts = [];

    public function criteria(Criteria $criteria): callable
    {
        return function ($data) use ($criteria) {
            foreach ($criteria->getFilters() as $expr) {
                $data = $this->filter($expr)($data);
            }
            $this->sorts = $criteria->getSorting();

            return $this->sort()($data);
        };
    }

    public function filter(Filter $filter): callable
    {
        if ($filter->isLogicalAnd() || $filter->isLogicalOr()) {
            return $this->filterLogical($filter);
        }

        return function ($data) use ($filter) {
            $subset = [];

            foreach ($data as $entity) {
                $value = $this->handleRecursiveRelationships($entity, $filter);

                if (match ($filter->operator) {
                    'eq' => $value === $filter->value,
                    'neq' => $value !== $filter->value,
                    'in' => in_array($value, $filter->value),
                    'notIn' => !in_array($value, $filter->value),
                    'gt' => $value > $filter->value,
                    'gte' => $value >= $filter->value,
                    'lt' => $value < $filter->value,
                    'lte' => $value <= $filter->value,
                    default => throw new NotImplementedDevException(message: sprintf('filter `%s` not implemented yet in method `%s`', $filter->operator, __METHOD__)),
                }) {
                    $subset[] = $entity;
                }
            }

            return $subset;
        };
    }

    public function parameter(Parameter $parameter): callable
    {
        return function ($data): void {
            throw $this->notAvailableException(__METHOD__);
        };
    }

    public function parameters(Criteria $criteria): array
    {
        $parameters = [];
        foreach ($criteria->getParameters() as $parameter) {
            $parameters[] = $this->parameter($parameter);
        }

        return $parameters;
    }

    public function transform(mixed $toTransform): mixed
    {
        throw $this->notAvailableException(__METHOD__);
    }

    public function reverseTransform(mixed $toReverse): mixed
    {
        throw $this->notAvailableException(__METHOD__);
    }

    private function sort(): callable
    {
        return function ($data) {
            if (empty($this->sorts)) {
                return $data;
            }

            $default = $this->sorts[0];
            $sort = function (mixed $a, mixed $b, Sort $current = null, ?int $position = 0) use (&$sort, $default) {
                $current ??= $default;
                ++$position;

                $valueA = $this->handleRecursiveRelationships($a, $current);
                $valueB = $this->handleRecursiveRelationships($b, $current);

                if ($valueA === $valueB && isset($this->sorts[$position])) {
                    return $sort($a, $b, $this->sorts[$position], $position);
                }

                return match (strtolower($current->value)) {
                    'asc' => $valueA > $valueB ? 1 : -1,
                    'desc' => $valueB > $valueA ? 1 : -1,
                    default => 0,
                };
            };
            uasort($data, $sort);

            return $data;
        };
    }

    private function filterLogical(Filter $filter): callable
    {
        return function ($data) use ($filter) {
            $subset = [];
            foreach ($data as $entity) {
                $assertions = 0;
                foreach ($filter->value as $expr) {
                    !empty($this->filter($expr)([$entity])) && $assertions++;
                }
                match (true) {
                    $filter->isLogicalOr() && $assertions > 0, $filter->isLogicalAnd() && $assertions === count($filter->value) => array_push($subset, $entity),
                    default => null,
                };
            }

            return $subset;
        };
    }

    /**
     * Recursively handle relationships values.
     * Example: "company.configuration.id" would return "$this->data[x]->$company->$configuration->$id" value.
     */
    private function handleRecursiveRelationships(mixed $entity, Filter|Sort $filter): mixed
    {
        foreach (explode('.', $filter->field) as $property) {
            $property = new ReflectionProperty($entity, $property);
            $value = $property->getValue($entity);
            $entity = $value;
        }

        return $value ?? null;
    }

    private function notAvailableException(string $method): NotImplementedDevException
    {
        return new NotImplementedDevException(message: sprintf('not available: %s', $method));
    }
}
