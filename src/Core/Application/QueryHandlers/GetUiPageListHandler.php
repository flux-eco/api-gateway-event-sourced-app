<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\QueryHandlers;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Application, Domain, Ports};

/**
 * @author martin@fluxlabs.ch
 */
class GetUiPageListHandler implements QueryHandler
{
    private string $getUiPageListHandlerName;
    private Ports\Configs\Outbounds $outbounds;

    private function __construct(
        string $getUiPageListHandlerName,
        Ports\Configs\Outbounds $outbounds
    ) {
        $this->getUiPageListHandlerName = $getUiPageListHandlerName;
        $this->outbounds = $outbounds;
    }

    public static function new(
        $getUiPageListHandlerName,
        Ports\Configs\Outbounds $outbounds
    ) : self {
        return new self(
            $getUiPageListHandlerName,
            $outbounds
        );
    }

    public function handle(Query $query, array $nextHandlers) : array
    {
        if ($query->getOperationName() !== $this->getUiPageListHandlerName) {
            return $this->process($query, $nextHandlers);
        }

        return $this->outbounds->getUserInterfaceClient()->getUiPageList();
    }

    public function process(Query $query, array $nextHandlers) : array
    {
        $nextHandler = $nextHandlers[0];

        unset($nextHandlers[0]); // remove item at index 0
        $nextHandlers = array_values($nextHandlers); // 'reindex' array

        return $nextHandler->handle($query, $nextHandlers);
    }
}