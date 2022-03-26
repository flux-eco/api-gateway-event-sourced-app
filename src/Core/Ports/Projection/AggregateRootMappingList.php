<?php

namespace FluxEco\ApiGateway\Core\Ports\Projection;

interface AggregateRootMappingList
{
    //public function getMappingForProjectionKey(string $projectionKey): AggregateRootMapping;
    /** @return AggregateRootMapping[] */
    public function getMappings(): array;
}