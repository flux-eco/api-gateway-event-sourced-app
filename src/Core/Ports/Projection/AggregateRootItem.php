<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Ports\Projection;

interface AggregateRootItem
{
    public function getAggregateRootName(): string;

    public function getAggregateRootId(): ?string;

    public function getData(): array;
}