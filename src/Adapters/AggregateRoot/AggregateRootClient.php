<?php


namespace FluxEco\ApiGatewayEventSourcedApp\Adapters\AggregateRoot;

use FluxEco\AggregateRoot\Adapters\Api\AggregateRootApi;
use FluxEco\ApiGatewayEventSourcedApp\Core\{Ports};

class AggregateRootClient implements Ports\AggregateRoot\AggregateRootClient
{
    private AggregateRootApi $aggregateRootApi;

    private function __construct(AggregateRootApi $aggregateRootApi)
    {
        $this->aggregateRootApi = $aggregateRootApi;
    }

    public static function new(): self
    {
        $aggregateRootApi = AggregateRootApi::new();
        return new self($aggregateRootApi);
    }

    final public function initializeAggregateRoots(): void
    {
        $this->aggregateRootApi->initializeAggregateRoots();
    }

    /**
     * @throws \JsonException
     */
    final public function create(string $correlationId, string $actorEmail, string $aggregateName, string $aggregateId, array $data): void
    {
        echo 'createItem'.PHP_EOL;
        $payload = json_encode($data, JSON_THROW_ON_ERROR);
        $this->aggregateRootApi->createAggregateRoot(
            $correlationId,
            $actorEmail,
            $aggregateName,
            $aggregateId,
            $payload
        );
    }

    final public function update(
        string $correlationId,
        string $actorEmail,
        string $aggregateRootName,
        string $aggregateRootId,
        array  $data
    ): void
    {
        $payload = json_encode($data);

        $this->aggregateRootApi->changeAggregateRoot(
            $correlationId,
            $actorEmail,
            $aggregateRootId,
            $aggregateRootName,
            $payload
        );
    }

    final public function delete(string $correlationId, string $actorEmail, string $aggregateRootName, string $aggregateId): void
    {
        $this->aggregateRootApi->deleteItem($correlationId, $actorEmail, $aggregateId, $aggregateRootName);
    }
}