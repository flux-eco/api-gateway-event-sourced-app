<?php


namespace FluxEco\ApiGateway\Adapters\Projection;

use FluxEco\ApiGateway\Core\{Ports};
use Flux\Eco\Projection\Adapters\Api\ProjectionApi;

class ProjectionClient implements Ports\Projection\ProjectionClient
{
    private ProjectionApi $projectionApi;

    private function __construct(ProjectionApi $projectionApi)
    {
        $this->projectionApi = $projectionApi;
    }

    public static function new(): self
    {
        $projectionApi = ProjectionApi::new();
        return new self($projectionApi);
    }

    final public function getItem(string $projectionName, string $projectionId): array
    {
        return $this->projectionApi->getItem($projectionName, $projectionId);
    }

    final public function getItemList(string $projectionName, array $filter): array
    {
        return $this->projectionApi->getItemList($projectionName, $filter);
    }

    /** @return ?AggregateRootMapping[] */
    final public function getAggregateRootMappingsForProjectionId(string $projectionId): ?array {
        $results = $this->projectionApi->getAggregateRootMappingsForProjectionId($projectionId);
        if($results !== null) {
            $return = [];
            foreach($results as $mapping) {
                $return[] = AggregateRootMapping::fromProjectionApiResult($mapping);
            }
            return $return;
        }

        return null;
    }

    final public function getAggregateRootMappingList(string $projectionName, string $projectionId, array $data, ?string $externalId = null): AggregateRootMappingList
    {
        $aggregateRootMappingsApiResult = $this->projectionApi->getAggregateRootMapping($projectionName, $projectionId, $data, $externalId);

        return AggregateRootMappingList::fromProjectionApiResult($aggregateRootMappingsApiResult);
    }

    final public function getProjectionIdForExternalIdIfExists(string $projectionName, string $externalId): ?string
    {
        return $this->projectionApi->getProjectionIdForExternalIdIfExists($projectionName, $externalId);
    }
}