<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\QueryHandlers;

/**
 * @author martin@fluxlabs.ch
 */
interface QueryHandler
{
    /**
     * @param QueryHandler[] $nextHandlers
     */
    public function handle(Query $query, array $nextHandlers) : array;
}