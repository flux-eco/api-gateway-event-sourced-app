<?php


namespace FluxEco\ApiGateway\Core\Ports\AggregateRoot;

interface AggregateRootProperty
{
    public function getAggregateRootName(): string;
    public function getAggregateRootId(): string;
    public function getAggreagetRootPropertyKey(): string;
    public function getAggregateRootPropertyValue(): string|int|bool|float;
}