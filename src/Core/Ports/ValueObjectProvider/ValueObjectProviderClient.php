<?php


namespace FluxEco\ApiGatewayEventSourcedApp\Core\Ports\ValueObjectProvider;

interface ValueObjectProviderClient
{
    public function createUuid(): string;
}