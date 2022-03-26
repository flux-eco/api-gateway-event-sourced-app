<?php


namespace FluxEco\ApiGateway\Adapters\Api;

use FluxEco\ApiGateway\{Core\Ports};
use FluxEco\ApiGateway\Adapters;

class GatewayApi
{

    private Ports\GatewayService $gatewayService;

    private function __construct(Ports\GatewayService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    public static function new(): self
    {
        $outbounds = Adapters\Configs\GatewayOutbounds::new();
        $gatewayService = Ports\GatewayService::new($outbounds);

        return new self($gatewayService);
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