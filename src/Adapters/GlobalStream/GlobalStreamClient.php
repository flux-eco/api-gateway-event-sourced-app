<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Adapters\GlobalStream;

use FluxEco\ApiGatewayEventSourcedApp\{Core\Ports};
use FluxEco\GlobalStream\Adapters\Api\GlobalStreamApi;

class GlobalStreamClient implements Ports\GlobalStream\GlobalStreamClient
{

    private function __construct()
    {
    }

    public static function new() : self
    {
        return new self();
    }

    public function initializeGlobalStream()
    {
        $globalStreamApi = GlobalStreamApi::new();
        $globalStreamApi->initializeGlobalStream();
    }
}