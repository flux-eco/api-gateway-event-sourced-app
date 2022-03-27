<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Domain\Models;
class CommandOperationsEnum
{
    public string $createItem = 'createItem';
    public string $updateItem = 'updateItem';
    public string $deleteItem = 'deleteItem';

    private function construct()
    {

    }

    public static function new(): self
    {
        return new self();
    }
}