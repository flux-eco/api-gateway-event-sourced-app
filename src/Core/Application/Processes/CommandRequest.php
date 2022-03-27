<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\Processes;

class CommandRequest
{
    private string $correlationId;
    private string $actorEmail;
    private string $requestUri;
    private array $requestContent;

    private function __construct(
        string $correlationId,
        string $actorEmail,
        string $requestUri,
        array $requestContent
    ) {
        $this->correlationId = $correlationId;
        $this->actorEmail = $actorEmail;
        $this->requestUri = $requestUri;
        $this->requestContent = $requestContent;
    }

    public static function new(
        string $correlationId,
        string $actorEmail,
        string $requestUri,
        array $requestContent
    ) {
        return new self(
            $correlationId,
            $actorEmail,
            $requestUri,
            $requestContent
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

    public function getRequestContent() : array
    {
        return $this->requestContent;
    }

}