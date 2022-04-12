<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\CommandHandlers;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Domain};
use FluxEco\ApiGatewayEventSourcedApp\Core\{Ports};

class UpdateItemByProjectionIdHandler
{
    private string $updateItemOperationName;
    private Ports\Outbounds $outbounds;

    private function __construct(
        string $updateItemOperationName,
        Ports\Outbounds $outbounds
    ) {
        $this->updateItemOperationName = $updateItemOperationName;
        $this->outbounds = $outbounds;
    }

    public static function  new(
        $updateItemOperationName,
        Ports\Outbounds $outbounds
    ) {
        return new self(
            $updateItemOperationName,
            $outbounds
        );
    }

    public function handle(Command $command, array $nextHandlers) : void
    {
        if ($command->getOperationName() !== $this->updateItemOperationName) {
            $this->process($command, $nextHandlers);
            return;
        }

        if (strlen($command->getProjectionId()) === 0) {
            $this->process($command, $nextHandlers);
            return;
        }

        $correlationId = $command->getCorrelationId();
        $actorEmail = $command->getActorEmail();
        $projectionName = $command->getProjectionName();
        $projectionId = $command->getProjectionId();
        $keyValueData = $command->getKeyValueData();

        $aggregateRootMappings = $this->outbounds->getAggregateRootMappingsForProjectionData($projectionName, $keyValueData);

        foreach ($aggregateRootMappings as $aggregateName => $aggregateKeyValueData) {

            $aggregateId = $this->outbounds->getAggregateIdForProjectionId($projectionName, $projectionId, $aggregateName);

            $this->outbounds->createAggregateRoot(
                $correlationId,
                $actorEmail,
                $aggregateId,
                $aggregateName,
                $aggregateKeyValueData
            );
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