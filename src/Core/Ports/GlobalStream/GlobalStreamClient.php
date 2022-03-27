<?php
declare(strict_types=1);

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Ports\GlobalStream;

interface GlobalStreamClient {
    public function initializeGlobalStream();
}