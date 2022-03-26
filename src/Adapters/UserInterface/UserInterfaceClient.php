<?php

namespace FluxEco\ApiGateway\Adapters\UserInterface;

use FluxEco\ApiGateway\Core\{Ports};
use Flux\Eco\UserInterface\Adapters\Api\UserInterfaceApi;

class UserInterfaceClient implements Ports\UserInterface\UserInterfaceClient
{
    private UserInterfaceApi $userInterfaceApi;

    private function __construct(UserInterfaceApi $userInterfaceApi)
    {
        $this->userInterfaceApi = $userInterfaceApi;
    }

    public static function new(): self
    {
        $userInterfaceApi = UserInterfaceApi::new();

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