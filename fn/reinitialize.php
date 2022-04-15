<?php

namespace fluxApiGatewayEventSourcedApp;

use FluxEco\ApiGatewayEventSourcedApp;

function reinitialize()
{
    ApiGatewayEventSourcedApp\Api::new()->reinitialize();
}