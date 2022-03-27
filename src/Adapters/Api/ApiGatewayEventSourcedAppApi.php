<?php


namespace FluxEco\ApiGatewayEventSourcedApp\Adapters\Api;

use FluxEco\ApiGatewayEventSourcedApp\{Core\Ports};
use FluxEco\ApiGatewayEventSourcedApp\Adapters;

class ApiGatewayEventSourcedAppApi
{

    private Ports\ApiGatewayEventSourcedAppService $gatewayService;

    private function __construct(Ports\ApiGatewayEventSourcedAppService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    public static function new(): self
    {
        $outbounds = Adapters\Configs\Outbounds::new();
        $gatewayService = Ports\ApiGatewayEventSourcedAppService::new($outbounds);

        return new self($gatewayService);
    }

    public function initialize() : void {
        $this->gatewayService->initialize();
    }

    final public function getNewCorrelationId(): string {
        return $this->gatewayService->getNewCorrelationId();
    }

    final public function storeItemByExternalId(string $correlationId, string $actorEmail, string $projectionName, string $externalId, array $data): void {
        $this->gatewayService->storeItemByExternalId($correlationId, $actorEmail, $projectionName, $externalId, $data);
    }

    final public function createItem(string $correlationId, string $actorEmail, string $projectionName, array $data): void
    {
        $this->gatewayService->createItem($correlationId, $actorEmail, $projectionName, $data);
    }

    final public function updteItem(string $correlationId, string $actorEmail, string $projectionName,  string $projectionId, array $data): void
    {
        $this->gatewayService->updteItem($correlationId, $actorEmail, $projectionName, $projectionId, $data);
    }

    final public function delete(string $correlationId, string $actorEmail, string $projectionName, string $projectionId): void
    {
        $this->gatewayService->delete($correlationId, $actorEmail, $projectionName, $projectionId);
    }

    final public function getItem(string $correlationId, string $actorEmail, string $projectionName, string $projectionId): array
    {
        return $this->gatewayService->getItem($correlationId, $actorEmail, $projectionName, $projectionId);
    }

    final public function getItemList(string $correlationId, string $actorEmail, string $projectionName, array $filter = []): array
    {
        return $this->gatewayService->getItemList($correlationId, $actorEmail, $projectionName, $filter);
    }

    final public function getUiPage(string $correlationId, string $actorEmail, string $projectionName): array
    {
        return $this->gatewayService->getUiPage($correlationId, $actorEmail, $projectionName);
    }

    final public function getUiPageList(string $correlationId, string $actorEmail): array
    {
        return $this->gatewayService->getUiPageList($correlationId, $actorEmail);
    }

}