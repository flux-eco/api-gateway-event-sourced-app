<?php

namespace fluxApiGatewayEventSourcedApp;

use FluxEco\ApiGatewayEventSourcedApp;

function query(
    string $correlationId,
    string $actorEmail,
    string $requestUri,
    array $requestContent
) : array {
    ApiGatewayEventSourcedApp\Api::new()->command($correlationId, $actorEmail, $requestUri, $requestContent);
}