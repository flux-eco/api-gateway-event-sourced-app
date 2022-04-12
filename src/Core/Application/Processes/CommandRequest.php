<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\Processes;

class CommandRequest
{
    private string $correlationId;
    private string $actorEmail;
    private string $requestUri;
    private array $projectionKeyValueData;

    private function __construct(
        string $correlationId,
        string $actorEmail,
        string $requestUri,
        array $projectionKeyValueData
    ) {
        $this->correlationId = $correlationId;
        $this->actorEmail = $actorEmail;
        $this->requestUri = $requestUri;
        $this->projectionKeyValueData = $projectionKeyValueData;
    }

    public static function new(
        string $correlationId,
        string $actorEmail,
        string $requestUri,
        array $projectionKeyValueData
    ) {
        return new self(
            $correlationId,
            $actorEmail,
            $requestUri,
            $projectionKeyValueData
        );
    }

    public function getRequestUri() : string
    {
        return $this->requestUri;
    }

    public function getCorrelationId() : string
    {
        return $this->correlationId;
    }

    public function getActorEmail() : string
    {
        return $this->actorEmail;
    }

    public function getProjectionKeyValueData() : array
    {
        return $this->projectionKeyValueData;
    }

}