<?php

namespace  FluxEco\ApiGateway\Core\Ports\Configs;
use  FluxEco\ApiGateway\Core\Ports;

interface GatewayOutbounds
{
   public function getAggregateRootClient(): Ports\AggregateRoot\AggregateRootClient;
   public function getProjectionClient(): Ports\Projection\ProjectionClient;
   public function getUserInterfaceClient(): Ports\UserInterface\UserInterfaceClient;
   public function getValueObjectProvider(): Ports\ValueObjectProvider\ValueObjectProviderClient;
}