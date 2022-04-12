<?php

namespace fluxApiGatewayEventSourcedApp;

use FluxEco\ApiGatewayEventSourcedApp;

function command(
    string $correlationId,
    string $actorEmail,
    string $requestUri,
    array $projectionKeyValueData
) : void {
    ApiGatewayEventSourcedApp\Api::new()->command($correlationId, $actorEmail, $requestUri, $projectionKeyValueData);
}