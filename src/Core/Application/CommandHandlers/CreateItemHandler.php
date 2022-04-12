<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\CommandHandlers;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Domain};
use FluxEco\ApiGatewayEventSourcedApp\Core\{Ports};

/**
 * @author martin@fluxlabs.ch
 */
class CreateItemHandler implements CommandHandler
{
    private string $createItemOperationName;
    private Ports\Outbounds $outbounds;

    private function __construct(
        string $createItemOperationName,
        Ports\Outbounds $outbounds
    ) {
        $this->createItemOperationName = $createItemOperationName;
        $this->outbounds = $outbounds;
    }

    public static function new(
        $createItemOperationName,
        Ports\Outbounds $outbounds
    ) : self {
        return new self(
            $createItemOperationName,
            $outbounds
        );
    }

    public function handle(Command $command, array $nextHandlers) : void
    {
        if ($command->getOperationName() !== $this->createItemOperationName) {
            $this->process($command, $nextHandlers);
            return;
        }

        $correlationId = $command->getCorrelationId();
        $actorEmail = $command->getActorEmail();
        $projectionName = $command->getProjectionName();
        $keyValueData = $command->getKeyValueData();

        $aggregateRootMappings = $this->outbounds->getAggregateRootMappingsForProjectionData($projectionName,
            $keyValueData);

        foreach ($aggregateRootMappings as $aggregateName => $aggregateKeyValueData) {

            $aggregateId = $this->outbounds->getNewUuid();

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