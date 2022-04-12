<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Adapters;

use FluxEco\ApiGatewayEventSourcedApp\{Adapters, Core\Ports};

use fluxAggregateRoot;
use fluxGlobalStream;
use fluxValueObject;
use fluxProjection;
use fluxUiTransformer;

class Outbounds implements Ports\Outbounds
{

    private function __construct()
    {

    }

    public static function new() : self
    {
        return new self();
    }

    final public function initializeAggregateRoots(): void
    {
        fluxAggregateRoot\initialize();
    }
    /**
     * @throws \JsonException
     */
    final public function createAggregateRoot(string $correlationId, string $actorEmail, string $aggregateId, string $aggregateName, array $data): void
    {
        $payload = json_encode($data, JSON_THROW_ON_ERROR);
        fluxAggregateRoot\create($correlationId, $actorEmail, $aggregateId, $aggregateName, $payload);
    }

    /**
     * @throws \JsonException
     */
    final public function updateAggregateRoot(string $correlationId, string $actorEmail, string $aggregateId, string $aggregateName, array $data): void
    {
        $payload = json_encode($data, JSON_THROW_ON_ERROR);
        fluxAggregateRoot\change($correlationId, $actorEmail, $aggregateId, $aggregateName, $payload);
    }

    final public function deleteAggregateRoot(string $correlationId, string $actorEmail, string $aggregateId, string $aggregateName): void
    {
        fluxAggregateRoot\delete($correlationId, $actorEmail, $aggregateId, $aggregateName);
    }

    final public function initializeGlobalStream(): void
    {
        fluxGlobalStream\initialize();
    }

    final public function initializeProjections(): void {
        fluxProjection\initialize();
    }

    final public function getNewUuid(): string
    {
        return fluxValueObject\getNewUuid();
    }

    final public function getAggregateRootMappingsForProjectionData(string $projectionName, array $keyValueData): array {
        return fluxProjection\getAggregateRootMappingsForProjectionData($projectionName, $keyValueData);
    }

    final public function getAggregateIdForProjectionId(string $projectionName, string $projectionId, string $aggregateName): ?string {
        return fluxProjection\getAggregateIdForProjectionId($projectionName, $projectionId, $aggregateName);
    }

    final public function getAggregateIdsForProjectionId(string $projectionName, string $projectionId): array {
        return fluxProjection\getAggregateIdsForProjectionId($projectionName, $projectionId);
    }

    final public function getProjectedItem(string $projectionName, string $projectionId): array
    {
        return fluxProjection\getItem($projectionName, $projectionId);
    }

    final public function getProjectedItemList(string $projectionName, array $filter): array
    {
        return fluxProjection\getItemList($projectionName, $filter);
    }

    public function getUiPage(string $projectionName): array
    {
        return fluxUiTransformer\getPageDefinition($projectionName);
    }

    public function getUiPageList(): array
    {
        return fluxUiTransformer\getPages();
    }
}