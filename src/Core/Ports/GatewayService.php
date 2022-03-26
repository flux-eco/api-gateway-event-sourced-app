<?php

namespace FluxEco\ApiGateway\Core\Ports;


class GatewayService
{
    private AggregateRoot\AggregateRootClient $aggregateRootClient;
    private Projection\ProjectionClient $projectionClient;
    private UserInterface\UserInterfaceClient $userInterfaceClient;
    private ValueObjectProvider\ValueObjectProviderClient $valueObjectProviderClient;

    private function __construct(
        AggregateRoot\AggregateRootClient             $aggregateRootClient,
        Projection\ProjectionClient                   $projectionClient,
        UserInterface\UserInterfaceClient             $userInterfaceClient,
        ValueObjectProvider\ValueObjectProviderClient $valueObjectProviderClient
    )
    {
        $this->aggregateRootClient = $aggregateRootClient;
        $this->projectionClient = $projectionClient;
        $this->userInterfaceClient = $userInterfaceClient;
        $this->valueObjectProviderClient = $valueObjectProviderClient;
    }

    public static function new(
        Configs\GatewayOutbounds $outbounds
    ): self
    {
        return new self(
            $outbounds->getAggregateRootClient(),
            $outbounds->getProjectionClient(),
            $outbounds->getUserInterfaceClient(),
            $outbounds->getValueObjectProvider()
        );
    }

    final public function getNewCorrelationId(): string
    {
        return $this->valueObjectProviderClient->createUuid();
    }

    final public function storeItemByExternalId(string $correlationId, string $actorEmail, string $projectionName, string $externalId, array $data): void
    {
        $projectionClient = $this->projectionClient;
        $projectionId = $projectionClient->getProjectionIdForExternalIdIfExists($projectionName, $externalId);
        if ($projectionId === null) {
            $this->createItem($correlationId, $actorEmail, $projectionName, $data, $externalId);
            return;
        }
        $this->updteItem($correlationId, $actorEmail, $projectionName, $projectionId, $data);
    }

    final public function createItem(string $correlationId, string $actorEmail, string $projectionName, array $data, ?string $externalId = null): void
    {
        $projectionId = $this->valueObjectProviderClient->createUuid();
        $aggregateRootMappingList = $this->projectionClient->getAggregateRootMappingList($projectionName, $projectionId, $data, $externalId);

        foreach ($aggregateRootMappingList->getMappings() as $key => $aggregateRootMapping) {
            $this->aggregateRootClient->create($correlationId, $actorEmail, $aggregateRootMapping->getAggregateName(), $aggregateRootMapping->getAggregateId(), $aggregateRootMapping->getProperties());
        }
    }

    final public function updteItem(string $correlationId, string $actorEmail, string $projectionName, string $projectionId, array $data): void
    {
        $aggregateRootMappingList = $this->projectionClient->getAggregateRootMappingList($projectionName, $projectionId, $data);

        foreach ($aggregateRootMappingList->getMappings() as $key => $aggregateRootMapping) {
            $this->aggregateRootClient->update($correlationId, $actorEmail, $aggregateRootMapping->getAggregateName(), $aggregateRootMapping->getAggregateId(), $aggregateRootMapping->getProperties());
        }
    }

    final public function delete(string $correlationId, string $actorEmail, string $projectionName, string $projectionId): void
    {
        $aggregateRootMappings = $this->projectionClient->getAggregateRootMappingsForProjectionId($projectionId);

        if ($aggregateRootMappings !== null) {
            foreach ($aggregateRootMappings as $mapping) {
                $this->aggregateRootClient->delete($correlationId, $actorEmail, $mapping->getAggregateName(), $mapping->getAggregateId());
            }
        }
    }

    final public function getItem(string $correlationId, string $actorEmail, string $projectionName, string $projectionId): array
    {
        return $this->projectionClient->getItem($projectionName, $projectionId);
    }

    final public function getItemList(string $correlationId, string $actorEmail, string $projectionName, array $filter): array
    {
        return $this->projectionClient->getItemList($projectionName, $filter);
    }

    final public function getUiPage(string $correlationId, string $actorEmail, string $representationName): array
    {
        return $this->userInterfaceClient->getUiPage($representationName);
    }

    final public function getUiPageList(string $correlationId, string $actorEmail): array
    {
        return $this->userInterfaceClient->getUiPageList();
    }
}