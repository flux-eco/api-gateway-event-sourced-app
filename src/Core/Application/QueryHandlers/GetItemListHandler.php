<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\QueryHandlers;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Application, Domain, Ports};

/**
 * @author martin@fluxlabs.ch
 */
class GetItemListHandler implements QueryHandler
{
    private string $getItemListHandler;
    private Ports\Configs\Outbounds $outbounds;

    private function __construct(
        string $getItemListHandler,
        Ports\Configs\Outbounds $outbounds
    ) {
        $this->getItemListHandler = $getItemListHandler;
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
        if ($query->getOperationName() !== $this->getItemListHandler) {
            return $this->process($query, $nextHandlers);
        }

        $items = $this->outbounds->getProjectionClient()->getItemList($query->getProjectionName(), $query->getRequestContent());
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