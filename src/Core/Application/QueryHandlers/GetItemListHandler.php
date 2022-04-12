<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\QueryHandlers;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Application, Domain, Ports};

/**
 * @author martin@fluxlabs.ch
 */
class GetItemListHandler implements QueryHandler
{
    private string $getItemListHandler;
    private Ports\Outbounds $outbounds;

    private function __construct(
        string $getItemListHandler,
        Ports\Outbounds $outbounds
    ) {
        $this->getItemListHandler = $getItemListHandler;
        $this->outbounds = $outbounds;
    }

    public static function new(
        $getItemOperationName,
        Ports\Outbounds $outbounds
    ) : self {
        return new self(
            $getItemOperationName,
            $outbounds
        );
    }

    public function handle(Query $query, array $nextHandlers) : array
    {
        if ($query->getOperationName() !== $this->getItemListHandler) {
            return $this->process($query, $nextHandlers);
        }

        $items = $this->outbounds->getProjectedItemList($query->getProjectionName(), $query->getRequestContent());
        return ['data' => $items, 'status' => 'success', 'total' => count($items)];
    }

    public function process(Query $query, array $nextHandlers) : array
    {
        $nextHandler = $nextHandlers[0];

        unset($nextHandlers[0]); // remove item at index 0
        $nextHandlers = array_values($nextHandlers); // 'reindex' array

        return $nextHandler->handle($query, $nextHandlers);
    }
}