<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Adapters\ValueObject;

use FluxEco\ApiGatewayEventSourcedApp\Core;
use FluxEco\ValueObject\Adapters\Api;


class ValueObjectClient implements Core\Ports\ValueObject\ValueObjectClient
{
    private Api\ValueObjectApi $objectProvider;

    private function __construct(Api\ValueObjectApi $objectProvider)
    {
        $this->objectProvider = $objectProvider;
    }

    public static function new(): self
    {
        $objectProvider = Api\ValueObjectApi::new();
        return new self($objectProvider);
    }

    final public function createUuid(): string
    {
        return $this->objectProvider->createUuid()->getValue();
    }
}