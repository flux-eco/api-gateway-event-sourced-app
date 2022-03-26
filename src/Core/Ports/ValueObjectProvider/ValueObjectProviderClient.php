<?php


namespace FluxEco\ApiGateway\Core\Ports\ValueObjectProvider;

interface ValueObjectProviderClient
{
    public function createUuid(): string;
}