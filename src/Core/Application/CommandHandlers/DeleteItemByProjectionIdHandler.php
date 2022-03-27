<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\CommandHandlers;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Domain};
use FluxEco\ApiGatewayEventSourcedApp\Core\{Ports};


/**
 * @author martin@fluxlabs.ch
 */
class DeleteItemByProjectionIdHandler {
    private string $updateItemOperationName;
    private Ports\AggregateRoot\AggregateRootClient $aggregateRootClient;
    private Ports\Projection\ProjectionClient $projectionClient;

    private function __construct(
        string $updateItemOperationName,
        Ports\AggregateRoot\AggregateRootClient $aggregateRootClient,
        Ports\Projection\ProjectionClient $projectionClient
    ) {
        $this->updateItemOperationName = $updateItemOperationName;
        $this->aggregateRootClient = $aggregateRootClient;
        $this->projectionClient = $projectionClient;
    }

    public static function new(
        $updateItemOperationName,
        Ports\Configs\Outbounds $outbounds
    ) {
        return new self(
            $updateItemOperationName,
            $outbounds->getAggregateRootClient(),
            $outbounds->getProjectionClient()
        );
    }

    public function handle(Command $command, array $nextHandlers) : void
    {
        if ($command->getOperationName() !== $this->updateItemOperationName) {
            $this->process($command, $nextHandlers);
        }

        if (strlen($command->getProjectionId()) === 0) {
            $this->process($command, $nextHandlers);
        }

        $correlationId = $command->getCorrelationId();
        $actorEmail = $command->getActorEmail();
        $projectionId = $command->getProjectionId();

        $aggregateRootMappings = $this->projectionClient->getAggregateRootMappingsForProjectionId($projectionId);

        if ($aggregateRootMappings !== null) {
            foreach ($aggregateRootMappings as $mapping) {
                $this->aggregateRootClient->delete($correlationId, $actorEmail, $mapping->getAggregateName(),
                    $mapping->getAggregateId());
            }
        }
    }

    public function process(Command $command, array $nextHandlers) : void
    {
        $nextHandler = $nextHandlers[0];

        unset($nextHandlers[0]); // remove item at index 0
        $nextHandlers = array_values($nextHandlers); // 'reindex' array

        $nextHandler->handle($command, $nextHandlers);
    }
}