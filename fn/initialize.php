<?php

namespace fluxApiGatewayEventSourcedApp;

use FluxEco\ApiGatewayEventSourcedApp;

function initialize()
{
    ApiGatewayEventSourcedApp\Api::new()->initialize();
}