<?php

namespace fluxApiGatewayEventSourcedApp;

use FluxEco\ApiGatewayEventSourcedApp;

function query(
    string $correlationId,
    string $actorEmail,
    string $requestUri,
    array $requestContent
) : array {
    return ApiGatewayEventSourcedApp\Api::new()->query($correlationId, $actorEmail, $requestUri, $requestContent);
}