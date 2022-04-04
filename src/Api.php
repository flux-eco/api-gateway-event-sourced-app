<?php

namespace FluxEco\ApiGatewayEventSourcedApp;

use FluxEco\ApiGatewayEventSourcedApp\{Core\Ports};
use FluxEco\ApiGatewayEventSourcedApp\Adapters;

class Api
{
    private Ports\ApiGatewayEventSourcedAppService $gatewayService;

    private function __construct(Ports\ApiGatewayEventSourcedAppService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    public static function new() : self
    {
        $outbounds = Adapters\Configs\Outbounds::new();
        $gatewayService = Ports\ApiGatewayEventSourcedAppService::new($outbounds);

        return new self($gatewayService);
    }

    public function initialize() : void
    {
        $this->gatewayService->initialize();
    }

    public function command(
        string $correlationId,
        string $actorEmail,
        string $requestUri,
        array $requestContent
    ) : void {
        $this->gatewayService->command($correlationId, $actorEmail, $requestUri, $requestContent);
    }

    public function query(
        string $correlationId,
        string $actorEmail,
        string $requestUri,
        array $requestContent
    ) : array {
        return $this->gatewayService->query($correlationId, $actorEmail, $requestUri, $requestContent);
    }

    /**
    final public function storeItemByExternalId(
        string $correlationId,
        string $actorEmail,
        string $projectionName,
        string $externalId,
        array $data
    ) : void {
        $this->gatewayService->storeItemByExternalId($correlationId, $actorEmail, $projectionName, $externalId, $data);
    }
     **/

}