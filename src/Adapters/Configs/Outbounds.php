<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Adapters\Configs;

use FluxEco\ApiGatewayEventSourcedApp\{Adapters, Core\Ports};

class Outbounds implements Ports\Configs\Outbounds
{

    private function __construct()
    {

    }

    public static function new() : self
    {
        return new self();
    }

    public function getAggregateRootClient() : Ports\AggregateRoot\AggregateRootClient
    {
        return Adapters\AggregateRoot\AggregateRootClient::new();
    }

    public function getGlobalStreamClient() : Ports\GlobalStream\GlobalStreamClient
    {
        return Adapters\GlobalStream\GlobalStreamClient::new();
    }

    public function getValueObjectClient() : Ports\ValueObject\ValueObjectClient
    {
        return Adapters\ValueObject\ValueObjectClient::new();
    }

    public function getProjectionClient() : Ports\Projection\ProjectionClient
    {
        return Adapters\Projection\ProjectionClient::new();
    }

    public function getUserInterfaceClient() : Ports\UserInterface\UserInterfaceClient
    {
        return Adapters\UserInterface\UserInterfaceClient::new();
    }
}