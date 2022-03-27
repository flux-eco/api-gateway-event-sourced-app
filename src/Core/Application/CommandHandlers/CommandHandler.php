<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\CommandHandlers;
/**
 * @author martin@fluxlabs.ch
 */
interface CommandHandler
{
    /**
     * @param CommandHandler[] $nextHandlers
     */
    public function handle(Command $command, array $nextHandlers) : void;
}