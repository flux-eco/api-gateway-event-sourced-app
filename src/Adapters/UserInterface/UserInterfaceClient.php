<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Adapters\UserInterface;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Ports};
use FluxEco\UiTransformer\Adapters\Api\UiTransformerApi;

class UserInterfaceClient implements Ports\UserInterface\UserInterfaceClient
{
    private UiTransformerApi $userInterfaceApi;

    private function __construct(UiTransformerApi $userInterfaceApi)
    {
        $this->userInterfaceApi = $userInterfaceApi;
    }

    public static function new(): self
    {
        $userInterfaceApi = UiTransformerApi::new();

        return new self($userInterfaceApi);
    }

    public function getUiPage(string $projectionName): array
    {
        return $this->userInterfaceApi->getPageDefinition($projectionName);
    }

    public function getUiPageList(): array
    {
        return $this->userInterfaceApi->getPages();
    }
}