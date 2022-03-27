<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Domain\Models;

use FluxEco\ApiGatewayEventSourcedApp\{Core\Ports};
use FluxEco\ApiGatewayEventSourcedApp\Adapters;

class OperationEndpoint
{
    private string $projectionName;
    private string $operationName;
    private string $projectionId;

    private function __construct(
        string $projectionName,
        string $operationName,
        string $projectionId
    ) {
        $this->projectionName = $projectionName;
        $this->operationName = $operationName;
        $this->projectionId = $projectionId;
    }

    public static function fromRequestUri(string $requestUri) : self
    {
        $pathParts = explode("/", $requestUri);

        $projectionName = $pathParts[4];
        $operationName = end($pathParts);

        $projectionId = '';
        $itemLabelKey = array_search('item', $pathParts);
        if ($itemLabelKey !== false) {
            $itemIdKey = $itemLabelKey + 1;
            $projectionId = $pathParts[$itemIdKey];
        }

        return new self($projectionName, $operationName, $projectionId);
    }

    public function getProjectionName() : string
    {
        return $this->projectionName;
    }

    public function getOperationName() : string
    {
        return $this->operationName;
    }

    public function getProjectionId() : string
    {
        return $this->projectionId;
    }
}