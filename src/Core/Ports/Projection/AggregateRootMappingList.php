<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Ports\Projection;

interface AggregateRootMappingList
{
    //public function getMappingForProjectionKey(string $projectionKey): AggregateRootMapping;
    /** @return AggregateRootMapping[] */
    public function getMappings(): array;
}