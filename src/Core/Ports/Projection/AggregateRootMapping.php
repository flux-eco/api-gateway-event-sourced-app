<?php


namespace FluxEco\ApiGateway\Core\Ports\Projection;

interface AggregateRootMapping
{
    public function getAggregateName(): string;
    public function getAggregateId(): string;
    public function getProperties(): array;
}