<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Ports;

use FluxEco\ApiGatewayEventSourcedApp\Core\Application\Processes;

class Service
{
    private Outbounds $outbounds;

    private function __construct(
        Outbounds $outbounds
    ) {
        $this->outbounds = $outbounds;
    }

    public static function new(
        Outbounds $outbounds
    ) : self {
        return new self(
            $outbounds
        );
    }

    final public function initialize() : void
    {
        $this->outbounds->initializeAggregateRoots();
        $this->outbounds->initializeGlobalStream();
        $this->outbounds->initializeProjections();
    }

    public function command(
        string $correlationId,
        string $actorEmail,
        string $requestUri,
        array $projectionKeyValueData
    ) : void {
        $commandRequest = Processes\CommandRequest::new($correlationId, $actorEmail, $requestUri, $projectionKeyValueData);
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

    /*final public function storeItemByExternalId(
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
    }*/

}