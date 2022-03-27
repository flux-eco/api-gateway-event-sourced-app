<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Domain\Models;

class QueryOperationsEnum
{

    public string $getItem = 'getItem';
    public string $getItemList = 'getItemList';
    public string $getPageList = 'getPageList';
    public string $getPage = 'getPage';


    private function construct()
    {

    }

    public static function new(): self
    {
        return new self();
    }
}