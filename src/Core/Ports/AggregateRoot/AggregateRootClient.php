<?php


namespace FluxEco\ApiGatewayEventSourcedApp\Core\Ports\AggregateRoot;

interface AggregateRootClient
{
    public function initializeAggregateRoots(): void;

    public function create(
        string $correlationId,
        string $actorEmail,
        string $aggregateRootName,
        string $aggregateId,
        array  $data
    ): void;

    public function update(
        string $correlationId,
        string $actorEmail,
        string $aggregateRootName,
        string $aggregateRootId,
        array  $data,
    ): void;

    public function delete(
        string $correlationId,
        string $actorEmail,
        string $aggregateRootName,
        string $aggregateId
    ): void;
}