<?php


namespace FluxEco\ApiGatewayEventSourcedApp\Core\Ports\ValueObject;

interface ValueObjectClient
{
    public function createUuid(): string;
}