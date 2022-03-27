<?php


namespace FluxEco\ApiGatewayEventSourcedApp\Core\Ports\Projection;

interface ProjectionClient
{
    public function initializeProjections(): void;

    public function getItem(string $projectionName, string $projectionId): array;

    public function getItemList(string $projectionName, array $filter): array;

    public function getAggregateRootMappingsForProjectionId(string $projectionId): ?array;

    public function getProjectionIdForExternalIdIfExists(string $projectionName, string $externalId): ?string;

    public function getAggregateRootMappingList(string $projectionName, string $projectionId, array $requestContent, ?string $externalId = null): AggregateRootMappingList;
}