<?php


namespace FluxEco\ApiGatewayEventSourcedApp\Core\Ports\UserInterface;

interface UserInterfaceClient
{
    public function getUiPage(string $projectionName): array;
    public function getUiPageList(): array;
}