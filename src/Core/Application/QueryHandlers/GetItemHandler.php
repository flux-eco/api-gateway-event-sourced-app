<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\QueryHandlers;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Application, Domain, Ports};

/**
 * @author martin@fluxlabs.ch
 */
class GetItemHandler implements QueryHandler
{
    private string $getItemOperationName;
    private Ports\Configs\Outbounds $outbounds;

    private function __construct(
        string $getItemOperationName,
        Ports\Configs\Outbounds $outbounds
    ) {
        $this->getItemOperationName = $getItemOperationName;
        $this->outbounds = $outbounds;
    }

    public static function new(
        $getItemOperationName,
        Ports\Configs\Outbounds $outbounds
    ) : self {
        return new self(
            $getItemOperationName,
            $outbounds
        );
    }

    public function handle(Query $query, array $nextHandlers) : array
    {
        if ($query->getOperationName() !== $this->getItemOperationName) {
            return $this->process($query, $nextHandlers);
        }

        return $this->outbounds->getProjectionClient()->getItem($query->getProjectionName(), $query->getProjectionId());
    }

    public function process(Query $query, array $nextHandlers) : array
    {
        $nextHandler = $nextHandlers[0];

        unset($nextHandlers[0]); // remove item at index 0
        $nextHandlers = array_values($nextHandlers); // 'reindex' array

       return $nextHandler->handle($query, $nextHandlers);
    }
}