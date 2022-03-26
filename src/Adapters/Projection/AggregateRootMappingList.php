<?php

namespace FluxEco\ApiGateway\Adapters\Projection;

use FluxEco\ApiGateway\{Core\Ports};
use Flux\Eco\Projection\Adapters\Api;

class AggregateRootMappingList implements Ports\Projection\AggregateRootMappingList
{
    /** @var Ports\Projection\AggregateRootMapping[] */
    private array $mappings = [];

    /** @param Ports\Projection\AggregateRootMapping[] $aggregateRootMappings */
    private function __construct(array $aggregateRootMappings)
    {
        $this->mappings = $aggregateRootMappings;
    }


    public static function fromProjectionApiResult(array $aggregateRootMappings): self
    {
        $mappings = [];
        foreach ($aggregateRootMappings as $aggregateRootMapping) {
            $mappings[] = AggregateRootMapping::fromProjectionApiResult($aggregateRootMapping);
        }

        return new self($mappings);
    }

    final public function getMappings(): array
    {
        return $this->mappings;
    }
}