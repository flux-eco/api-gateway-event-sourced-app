<?php

namespace  FluxEco\ApiGatewayEventSourcedApp\Core\Ports;
use  FluxEco\ApiGatewayEventSourcedApp\Core\Ports;
use FluxEco\ApiGatewayEventSourcedApp\Core\Ports\Projection;

interface Outbounds
{
    public function initializeAggregateRoots(): void;
    public function createAggregateRoot(string $correlationId, string $actorEmail, string $aggregateId, string $aggregateName, array $data): void;
    public function updateAggregateRoot(string $correlationId, string $actorEmail, string $aggregateId, string $aggregateName, array $data): void;
    public function deleteAggregateRoot(string $correlationId, string $actorEmail, string $aggregateId, string $aggregateName): void;

    public function initializeGlobalStream(): void;

    public function initializeProjections(): void;

    public function reinitializeProjections(): void;

    public function getAggregateRootMappingsForProjectionData(string $projectionName, array $keyValueData): array;
    public function getAggregateIdForProjectionId(string $projectionName, string $projectionId, string $aggregateName): ?string;
    public function getAggregateIdsForProjectionId(string $projectionName, string $projectionId): array;

    public function getProjectedItem(string $projectionName, string $projectionId): array;
    public function getProjectedItemList(string $projectionName, array $filter): array;

    public function getNewUuid(): string;
}