<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\Processes;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Application\CommandHandlers\Command,
    Domain,
    Ports,
    Application
};

/**
 * @author: martin@fluxlabs.ch
 */
class CommandRequestProcess implements Application\CommandHandlers\CommandHandler
{
    /**
     * @var Application\CommandHandlers\CommandHandler[]
     */
    private array $handlerQueue;

    private function __construct(Ports\Outbounds $outbounds)
    {
        $this->handlerQueue = [
            Application\CommandHandlers\CreateItemHandler::new(
                Domain\Models\CommandOperationsEnum::new()->createItem,
                $outbounds
            ),
            Application\CommandHandlers\UpdateItemByProjectionIdHandler::new(
                Domain\Models\CommandOperationsEnum::new()->updateItem,
                $outbounds
            ),
            Application\CommandHandlers\DeleteItemByProjectionIdHandler::new(
                Domain\Models\CommandOperationsEnum::new()->deleteItem,
                $outbounds
            ),
            $this
        ];
    }

    public static function new(Ports\Outbounds $outbounds) : self
    {
        return new self($outbounds);
    }

    public function process(CommandRequest $commandRequest) : void
    {
        $operationEndpoint = Domain\Models\OperationEndpoint::fromRequestUri($commandRequest->getRequestUri());
        $command = Application\CommandHandlers\Command::new(
            $commandRequest->getRequestUri(),
            $commandRequest->getCorrelationId(),
            $commandRequest->getActorEmail(),
            $operationEndpoint->getOperationName(),
            $operationEndpoint->getProjectionName(),
            $commandRequest->getProjectionKeyValueData(),
            $operationEndpoint->getProjectionId()
        );

        $nextHandler = $this->handlerQueue[0];
        unset($this->handlerQueue[0]); // remove item at index 0
        $nextHandlers = array_values($this->handlerQueue); // 'reindex' array

        $nextHandler->handle($command, $nextHandlers);
    }

    public function handle(Command $command, array $nextHandlers) : void
    {
        throw new \RuntimeException('No valid operation found in requestUri: ' . $command->getRequestUri() . ' for operation: ' . $command->getOperationName());
    }
}