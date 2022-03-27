<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Ports;

use FluxEco\ApiGatewayEventSourcedApp\Core\Application\Processes;

class ApiGatewayEventSourcedAppService
{
    private Configs\Outbounds $outbounds;

    private function __construct(
        Configs\Outbounds $outbounds
    ) {
        $this->outbounds = $outbounds;
    }

    public static function new(
        Configs\Outbounds $outbounds
    ) : self {
        return new self(
            $outbounds
        );
    }

    final public function initialize() : void
    {
        $this->outbounds->getAggregateRootClient()->initializeAggregateRoots();
        $this->outbounds->getGlobalStreamClient()->initializeGlobalStream();
        $this->outbounds->getProjectionClient()->initializeProjections();
    }

    public function command(
        string $correlationId,
        string $actorEmail,
        string $requestUri,
        array $requestContent
    ) : void {
        $commandRequest = Processes\CommandRequest::new($correlationId, $actorEmail, $requestUri, $requestContent);
        Processes\CommandRequestProcess::new($this->outbounds)->process($commandRequest);
    }

    public function query(
        string $correlationId,
        string $actorEmail,
        string $requestUri,
        array $requestContent
    ) : array {
        $queryRequest = Processes\QueryRequest::new($correlationId, $actorEmail, $requestUri, $requestContent);
        return Processes\QueryRequestProcess::new($this->outbounds)->process($queryRequest);
    }

    final public function storeItemByExternalId(
        string $correlationId,
        string $actorEmail,
        string $projectionName,
        string $externalId,
        array $data
    ) : void {
        $projectionClient = $this->projectionClient;
        $projectionId = $projectionClient->getProjectionIdForExternalIdIfExists($projectionName, $externalId);
        if ($projectionId === null) {
            $this->createItem($correlationId, $actorEmail, $projectionName, $data, $externalId);
            return;
        }
        $this->updteItem($correlationId, $actorEmail, $projectionName, $projectionId, $data);
    }

}