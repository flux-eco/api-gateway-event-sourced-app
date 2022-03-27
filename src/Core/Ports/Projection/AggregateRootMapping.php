<?php


namespace FluxEco\ApiGatewayEventSourcedApp\Core\Ports\Projection;

interface AggregateRootMapping
{
    public function getAggregateName(): string;
    public function getAggregateId(): string;
    public function getProperties(): array;
}