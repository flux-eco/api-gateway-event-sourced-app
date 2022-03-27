<?php

namespace  FluxEco\ApiGatewayEventSourcedApp\Core\Ports\Configs;
use  FluxEco\ApiGatewayEventSourcedApp\Core\Ports;

interface Outbounds
{
   public function getAggregateRootClient(): Ports\AggregateRoot\AggregateRootClient;
   public function getGlobalStreamClient(): Ports\GlobalStream\GlobalStreamClient;
   public function getProjectionClient(): Ports\Projection\ProjectionClient;
   public function getUserInterfaceClient(): Ports\UserInterface\UserInterfaceClient;
   public function getValueObjectProvider(): Ports\ValueObjectProvider\ValueObjectProviderClient;
}