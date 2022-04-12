<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\QueryHandlers;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Application, Domain, Ports};

/**
 * @author martin@fluxlabs.ch
 */
class GetUiPageHandler implements QueryHandler
{
    private string $getUiPageHandler;
    private Ports\Outbounds $outbounds;

    private function __construct(
        string $getUiPageHandler,
        Ports\Outbounds $outbounds
    ) {
        $this->getUiPageHandler = $getUiPageHandler;
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
        if ($query->getOperationName() !== $this->getUiPageHandler) {
            return $this->process($query, $nextHandlers);
        }

        return $this->outbounds->getUiPage($query->getProjectionName());
    }

    public function process(Query $query, array $nextHandlers) : array
    {
        $nextHandler = $nextHandlers[0];

        unset($nextHandlers[0]); // remove item at index 0
        $nextHandlers = array_values($nextHandlers); // 'reindex' array

        return $nextHandler->handle($query, $nextHandlers);
    }
}