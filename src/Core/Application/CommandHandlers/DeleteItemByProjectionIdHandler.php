<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\CommandHandlers;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Domain};
use FluxEco\ApiGatewayEventSourcedApp\Core\{Ports};


/**
 * @author martin@fluxlabs.ch
 */
class DeleteItemByProjectionIdHandler {
    private string $deleteItemOperationName;
    private Ports\AggregateRoot\AggregateRootClient $aggregateRootClient;
    private Ports\Projection\ProjectionClient $projectionClient;

    private function __construct(
        string $deleteItemOperationName,
        Ports\AggregateRoot\AggregateRootClient $aggregateRootClient,
        Ports\Projection\ProjectionClient $projectionClient
    ) {
        $this->deleteItemOperationName = $deleteItemOperationName;
        $this->aggregateRootClient = $aggregateRootClient;
        $this->projectionClient = $projectionClient;
    }

    public static function new(
        $deleteItemOperationName,
        Ports\Configs\Outbounds $outbounds
    ) {
        return new self(
            $deleteItemOperationName,
            $outbounds->getAggregateRootClient(),
            $outbounds->getProjectionClient()
        );
    }

    public function handle(Command $command, array $nextHandlers) : void
    {
        if ($command->getOperationName() !== $this->deleteItemOperationName) {
            $this->process($command, $nextHandlers);
            return;
        }

        if (strlen($command->getProjectionId()) === 0) {
            echo "no projectionID given for command ".print_r($command, true);
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