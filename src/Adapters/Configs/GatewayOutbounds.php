<?php


namespace FluxEco\ApiGateway\Adapters\Configs;

use FluxEco\ApiGateway\{Adapters, Core\Ports};

class GatewayOutbounds implements Ports\Configs\GatewayOutbounds
{

    private function __construct()
    {

    }

    public static function new(): self
    {
        return new self();
    }

    public function getAggregateRootClient(): Ports\AggregateRoot\AggregateRootClient
    {
        return Adapters\AggregateRoot\AggregateRootClient::new();
    }

    public function getValueObjectProvider(): Ports\ValueObjectProvider\ValueObjectProviderClient
    {
        return Adapters\ValueObjectProvider\ValueObjectProviderClient::new();
    }

    public function getProjectionClient(): Ports\Projection\ProjectionClient
    {
        return Adapters\Projection\ProjectionClient::new();
    }

    public function getUserInterfaceClient(): Ports\UserInterface\UserInterfaceClient
    {
        return Adapters\UserInterface\UserInterfaceClient::new();
    }
}