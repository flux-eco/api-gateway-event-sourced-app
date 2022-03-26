<?php

namespace FluxEco\ApiGateway\Adapters\ValueObjectProvider;

use FluxEco\ApiGateway\Core;
use Flux\Eco\ObjectProvider\ValueObject\Adapters\Api;


class ValueObjectProviderClient implements Core\Ports\ValueObjectProvider\ValueObjectProviderClient
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