<?php

namespace FluxEco\ApiGateway\Adapters\Projection;

use FluxEco\ApiGateway\{Core\Ports};
use Flux\Eco\Projection\Adapters\Api;


class AggregateRootMapping implements Ports\Projection\AggregateRootMapping
{
    private string $aggregateName;
    private string $aggregateId;
    private array $properties;

    private function __construct(
        string $aggregateName,
        string $aggregateId,
        array $properties
    )
    {
        $this->aggregateName = $aggregateName;
        $this->aggregateId = $aggregateId;
        $this->properties = $properties;
    }

    public static function fromProjectionApiResult(
        Api\RootObjectMapping $aggregateRootMapping
    ): self
    {
        return new self(
            $aggregateRootMapping->getAggregateName(),
            $aggregateRootMapping->getAggregateId(),
            $aggregateRootMapping->getProperties()
        );
    }

    final public function getAggregateName(): string
    {
        return $this->aggregateName;
    }

    final public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    final public function getProperties(): array
    {
        return $this->properties;
    }
}