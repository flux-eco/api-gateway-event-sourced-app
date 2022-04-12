<?php

require_once __DIR__ . '/../vendor/autoload.php';

FluxEco\DotEnv\Api::new()->load(__DIR__);

//initialize
fluxApiGatewayEventSourcedApp\initialize();


//create item
$correlationId = fluxValueObject\getNewUuid();
$actorEmail = 'example@fluxlabs.ch';
$requestUri = '/api/v1/command/account/createItem';
$projectionKeyValueData = [
    "firstname" => "Emmett",
    "lastname" => "Brown"
];
fluxApiGatewayEventSourcedApp\command($correlationId, $actorEmail, $requestUri, $projectionKeyValueData);
echo '/api/v1/command/account/createItem handled '.PHP_EOL.PHP_EOL;


//get item list
$requestUri = '/api/v1/query/account/getItemList';
$itemList = fluxApiGatewayEventSourcedApp\query($correlationId, $actorEmail, $requestUri, []);
echo '/api/v1/query/{projectionName}/getItemList handled: '.PHP_EOL;
print_r($itemList);
echo PHP_EOL.PHP_EOL;


//change item
$correlationId = fluxValueObject\getNewUuid();
$projectionId = $itemList['data'][0]['projectionId'];
$projectionKeyValueData = [
    "firstname" => "Dr. Emmett",
    "lastname" => "Brown"
];
$requestUri = '/api/v1/command/account/item/'.$projectionId.'/updateItem';
fluxApiGatewayEventSourcedApp\command($correlationId, $actorEmail, $requestUri, $projectionKeyValueData);
echo '/api/v1/command/{projectionName}/item/{projectionId}/updateItem handled: '.PHP_EOL.PHP_EOL;


//get item
$requestUri = '/api/v1/query/account/item/'.$projectionId.'/getItem';
$item = fluxApiGatewayEventSourcedApp\query($correlationId, $actorEmail, $requestUri, []);
echo '/api/v1/query/{projectionName}/item/{projectionId}/getItem handeld: '.PHP_EOL.PHP_EOL;
print_r($item);
echo PHP_EOL.PHP_EOL;


//delete item
$requestUri = '/api/v1/command/account/item/'.$projectionId.'/deleteItem';
fluxApiGatewayEventSourcedApp\command($correlationId, $actorEmail, $requestUri, []);
echo '/api/v1/command/{projectionName}/item/{projectionId}/deleteItem handeld: '.PHP_EOL.PHP_EOL;


//get page list
$requestUri = '/api/v1/query/getPageList';
$pageList = fluxApiGatewayEventSourcedApp\query($correlationId, $actorEmail, $requestUri, []);
echo '/api/v1/query/getPageList handeld: '.PHP_EOL.PHP_EOL;
print_r($pageList);


//get page
$requestUri = '/api/v1/query/account/getPage';
$page = fluxApiGatewayEventSourcedApp\query($correlationId, $actorEmail, $requestUri, []);
echo '/api/v1/query/getPage handeld: '.PHP_EOL.PHP_EOL;
print_r($page);


