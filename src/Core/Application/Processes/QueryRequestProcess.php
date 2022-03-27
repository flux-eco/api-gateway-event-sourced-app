<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\Processes;

use FluxEco\ApiGatewayEventSourcedApp\Core\{
    Domain,
    Ports,
    Application
};

/**
 * @author: martin@fluxlabs.ch
 */
class QueryRequestProcess implements Application\QueryHandlers\QueryHandler
{
    /**
     * @var Application\QueryHandlers\QueryHandler[]
     */
    private array $handlerQueue;

    private function __construct(Ports\Configs\Outbounds $outbounds)
    {
        $this->handlerQueue = [
            Application\QueryHandlers\GetItemHandler::new(
                Domain\Models\QueryOperationsEnum::new()->getItem,
                $outbounds
            ),
            Application\QueryHandlers\GetItemListHandler::new(
                Domain\Models\QueryOperationsEnum::new()->getItemList,
                $outbounds
            ),
            Application\QueryHandlers\GetUiPageHandler::new(
                Domain\Models\QueryOperationsEnum::new()->getPage,
                $outbounds
            ),
            Application\QueryHandlers\GetItemListHandler::new(
                Domain\Models\QueryOperationsEnum::new()->getPageList,
                $outbounds
            ),
            $this
        ];
    }

    public static function new(Ports\Configs\Outbounds $outbounds) : self
    {
        return new self($outbounds);
    }

    public function process(QueryRequest $queryRequest) : array
    {
        $operationEndpoint = Domain\Models\OperationEndpoint::fromRequestUri($queryRequest->getRequestUri());

        $command = Application\QueryHandlers\Query::new(
            $queryRequest->getRequestUri(),
            $queryRequest->getCorrelationId(),
            $queryRequest->getActorEmail(),
            $operationEndpoint->getOperationName(),
            $operationEndpoint->getProjectionName(),
            $queryRequest->getRequestContent(),
            $operationEndpoint->getProjectionId()
        );

        $nextHandler = $this->handlerQueue[0];
        unset($this->handlerQueue[0]); // remove item at index 0
        $nextHandlers = array_values($this->handlerQueue); // 'reindex' array

        return $nextHandler->handle($command, $nextHandlers);
    }

    public function handle(Application\QueryHandlers\Query $query, array $nextHandlers) : array
    {
        throw new \RuntimeException('No valid operation found in requestUri: ' . $query->getRequestUri() . ' for operation: ' . $query->getOperationName());
    }
}