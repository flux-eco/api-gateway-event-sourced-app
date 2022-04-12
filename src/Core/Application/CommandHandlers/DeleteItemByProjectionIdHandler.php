<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\CommandHandlers;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Domain};
use FluxEco\ApiGatewayEventSourcedApp\Core\{Ports};


/**
 * @author martin@fluxlabs.ch
 */
class DeleteItemByProjectionIdHandler {
    private string $deleteItemOperationName;
    private Ports\Outbounds $outbounds;

    private function __construct(
        string $deleteItemOperationName,
        Ports\Outbounds $outbounds
    ) {
        $this->deleteItemOperationName = $deleteItemOperationName;
        $this->outbounds = $outbounds;
    }

    public static function new(
        $deleteItemOperationName,
        Ports\Outbounds $outbounds
    ) {
        return new self(
            $deleteItemOperationName,
            $outbounds
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
        $projectionName = $command->getProjectionName();
        $projectionId = $command->getProjectionId();

        $aggregateIds = $this->outbounds->getAggregateIdsForProjectionId($projectionName, $projectionId);

        foreach ($aggregateIds  as $aggregateName => $aggregateId) {
            $this->outbounds->deleteAggregateRoot($correlationId, $actorEmail, $aggregateId, $aggregateName);
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