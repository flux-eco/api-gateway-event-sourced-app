<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\CommandHandlers;

/**
 * @author martin@fluxlabs.ch
 */
class Command
{
    private string $requestUri;
    private string $correlationId;
    private string $actorEmail;
    private string $operationName;
    private string $projectionName;
    private string $projectionId;
    private array $requestContent;
    private string $externalId;

    private function __construct(
        string $requestUri,
        string $correlationId,
        string $actorEmail,
        string $operationName,
        string $projectionName,
        array $requestContent,
        string $projectionId,
        string $externalId
    ) {
        $this->requestUri = $requestUri;
        $this->correlationId = $correlationId;
        $this->actorEmail = $actorEmail;
        $this->operationName = $operationName;
        $this->projectionName = $projectionName;
        $this->requestContent = $requestContent;
        $this->projectionId = $projectionId;
        $this->externalId = $externalId;
    }

    public static function new(
        string $requestUri,
        string $correlationId,
        string $actorEmail,
        string $operationName,
        string $projectionName,
        array $requestContent,
        string $projectionId = '',
        string $externalId = ''
    ) {
        return new self(
            $requestUri,
            $correlationId,
            $actorEmail,
            $operationName,
            $projectionName,
            $requestContent,
            $projectionId,
            $externalId
        );
    }

    public function getExternalId() : string
    {
        return $this->externalId;
    }

    public function getRequestUri() : string
    {
        return $this->requestUri;
    }

    public function getCorrelationId() : string
    {
        return $this->correlationId;
    }

    public function getRequestContent() : array
    {
        return $this->requestContent;
    }

    public function getOperationName() : string
    {
        return $this->operationName;
    }

    public function getActorEmail() : string
    {
        return $this->actorEmail;
    }

    public function getProjectionName() : string
    {
        return $this->projectionName;
    }

    public function getProjectionId() : string
    {
        return $this->projectionId;
    }

}