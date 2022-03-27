<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\CommandHandlers;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Domain};
use FluxEco\ApiGatewayEventSourcedApp\Core\{Ports};

class UpdateItemByProjectionIdHandler
{
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

    public static function  new(
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
        $projectionName = $command->getProjectionName();
        $projectionId = $command->getProjectionId();
        $requestContent = $command->getRequestContent();

        $aggregateRootMappingList = $this->projectionClient->getAggregateRootMappingList($projectionName, $projectionId,
            $requestContent);

        foreach ($aggregateRootMappingList->getMappings() as $key => $aggregateRootMapping) {
            $this->aggregateRootClient->update($correlationId, $actorEmail, $aggregateRootMapping->getAggregateName(),
                $aggregateRootMapping->getAggregateId(), $aggregateRootMapping->getProperties());
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