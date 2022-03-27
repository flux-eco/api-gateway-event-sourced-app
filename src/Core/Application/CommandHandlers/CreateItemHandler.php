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
    private Ports\AggregateRoot\AggregateRootClient $aggregateRootClient;
    private Ports\Projection\ProjectionClient $projectionClient;
    private Ports\ValueObject\ValueObjectClient $valueObjectProviderClient;

    private function __construct(
        string $createItemOperationName,
        Ports\AggregateRoot\AggregateRootClient $aggregateRootClient,
        Ports\Projection\ProjectionClient $projectionClient,
        Ports\ValueObject\ValueObjectClient $valueObjectProviderClient
    )
    {
        $this->createItemOperationName = $createItemOperationName;
        $this->aggregateRootClient = $aggregateRootClient;
        $this->projectionClient = $projectionClient;
        $this->valueObjectProviderClient = $valueObjectProviderClient;
    }

    public static function new(
        $createItemOperationName,
        Ports\Configs\Outbounds $outbounds
    ): self {
        return new self(
            $createItemOperationName,
            $outbounds->getAggregateRootClient(),
            $outbounds->getProjectionClient(),
            $outbounds->getValueObjectClient()
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
        $projectionId = $this->valueObjectProviderClient->createUuid();
        $requestContent = $command->getRequestContent();
        $externalId = $command->getExternalId();

        $aggregateRootMappingList = $this->projectionClient->getAggregateRootMappingList(
            $projectionName,
            $projectionId,
            $requestContent,
            $externalId
        );

        foreach ($aggregateRootMappingList->getMappings() as $aggregateRootMapping) {
            $this->aggregateRootClient->create($correlationId, $actorEmail, $aggregateRootMapping->getAggregateName(),
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