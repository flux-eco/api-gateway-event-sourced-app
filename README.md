# flux-eco/api-gateway-event-sourced-app

Manage all requests from outside for an event sourced application. Up to now this
component is binded to the other flux-eco components. You can bind your own components by implementing an adapter
and exchange the Adapters\Configs\Outbound class.

The following example application demonstrates the usage:
https://github.com/flux-caps/todo-app

## Usage

.env
```
AGGREGATE_ROOT_SCHEMA_DIRECTORY=schemas/domain
AGGREGATE_ROOT_STORAGE_CONFIG_ENV_PREFIX=EVENTS_
AGGREGATE_ROOT_EVENT_SCHEMA_FILE_PATH=../vendor/flux-eco/aggregate-root/schemas/AggregateRootEvent.yaml
EVENTS_STORAGE_HOST=localhost
EVENTS_STORAGE_DRIVER=Pdo_Mysql
EVENTS_STORAGE_NAME=events
EVENTS_STORAGE_USER=user
EVENTS_STORAGE_PASSWORD=password
PROJECTION_APP_SCHEMA_DIRECTORY=../vendor/flux-eco/projection/schemas
PROJECTION_ECO_SCHEMA_DIRECTORY=schemas/projections
PROJECTION_STORAGE_CONFIG_ENV_PREFIX=PROJECTION_
PROJECTION_STORAGE_NAME=projection
PROJECTION_STORAGE_HOST=localhost
PROJECTION_STORAGE_DRIVER=Pdo_Mysql
PROJECTION_STORAGE_USER=user
PROJECTION_STORAGE_PASSWORD=password
STREAM_STORAGE_CONFIG_ENV_PREFIX=STREAM_
STREAM_STORAGE_NAME=stream
STREAM_STORAGE_HOST=localhost
STREAM_STORAGE_DRIVER=Pdo_Mysql
STREAM_STORAGE_USER=user
STREAM_STORAGE_PASSWORD=password
STREAM_TABLE_NAME=stream
STREAM_STATE_SCHEMA_FILE=../vendor/flux-eco/global-stream/schemas/State.yaml
UI_TRANSFORM_TRANSLATION_FILES_DIRECTORY=configs/translations
UI_TRANSFORM_UI_DEFINITION_DIRECTORY=configs/ui
UI_TRANSFORM_PAGE_LIST_DEFINITION_FILE_PATH=configs/ui/pages.yaml
UI_TRANSFORM_MARKDOWN_TO_HTML_CONVERTER_REST_API_URL=http://localhost:9001
```

schemas and configs
```
@see examples/schemas and examples/configs
```

example.php
```
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
```

outputs
``` 
/api/v1/command/account/createItem handled 

/api/v1/query/{projectionName}/getItemList handled: 
Array
(
    [data] => Array
        (
            [0] => Array
                (
                    [projectionId] => 6229121e-e5d5-4ada-9fb2-01b579adea2f
                    [firstname] => Emmett
                    [lastname] => Brown
                )
        )

    [status] => success
    [total] => 1
)


/api/v1/command/{projectionName}/item/{projectionId}/updateItem handled: 

/api/v1/query/{projectionName}/item/{projectionId}/getItem handeld: 

Array
(
    [projectionId] => 6229121e-e5d5-4ada-9fb2-01b579adea2f
    [firstname] => Dr. Emmett
    [lastname] => Brown
)


/api/v1/command/{projectionName}/item/{projectionId}/deleteItem handeld: 

/api/v1/query/getPageList handeld: 

Array
(
    [data] => Array
        (
            [0] => Array
                (
                    [title] => Account
                    [url] => /listdata/Topic
                    [avatar] => /icons/seminars.png
                    [pageType] => ListData
                    [projectionName] => account
                    [editForm] => Array
                        (
                            [properties] => Array
                                (
                                    [0] => Array
                                        (
                                            [title] => Firstname
                                            [key] => firstname
                                            [dataIndex] => firstname
                                            [valueType] => string
                                            [width] => m
                                            [formItemProps] => Array
                                                (
                                                    [rules] => Array
                                                        (
                                                            [0] => Array
                                                                (
                                                                    [required] => 1
                                                                    [message] => Please enter a firstname
                                                                )

                                                        )

                                                )

                                        )

                                    [1] => Array
                                        (
                                            [title] => Lastname
                                            [key] => lastname
                                            [dataIndex] => lastname
                                            [valueType] => string
                                            [width] => m
                                            [formItemProps] => Array
                                                (
                                                )

                                        )

                                )

                        )

                    [createForm] => Array
                        (
                            [properties] => Array
                                (
                                    [0] => Array
                                        (
                                            [title] => Firstname
                                            [key] => firstname
                                            [dataIndex] => firstname
                                            [valueType] => string
                                            [width] => m
                                            [formItemProps] => Array
                                                (
                                                    [rules] => Array
                                                        (
                                                            [0] => Array
                                                                (
                                                                    [required] => 1
                                                                    [message] => Please enter a firstname
                                                                )

                                                        )

                                                )

                                        )

                                    [1] => Array
                                        (
                                            [title] => Lastname
                                            [key] => lastname
                                            [dataIndex] => lastname
                                            [valueType] => string
                                            [width] => m
                                            [formItemProps] => Array
                                                (
                                                )

                                        )

                                )

                        )

                    [itemActions] => Array
                        (
                            [edit] => Array
                                (
                                    [type] => form
                                    [key] => editForm
                                    [title] => edit
                                )

                            [delete] => Array
                                (
                                    [type] => form
                                    [key] => deleteForm
                                    [title] => delete
                                )

                        )

                )

        )

    [status] => success
    [total] => 1
)
/api/v1/query/getPage handeld: 

Array
(
    [title] => Account
    [url] => /listdata/Topic
    [avatar] => /icons/seminars.png
    [pageType] => ListData
    [projectionName] => account
    [editForm] => Array
        (
            [properties] => Array
                (
                    [0] => Array
                        (
                            [title] => Firstname
                            [key] => firstname
                            [dataIndex] => firstname
                            [valueType] => string
                            [width] => m
                            [formItemProps] => Array
                                (
                                    [rules] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [required] => 1
                                                    [message] => Please enter a firstname
                                                )

                                        )

                                )

                        )

                    [1] => Array
                        (
                            [title] => Lastname
                            [key] => lastname
                            [dataIndex] => lastname
                            [valueType] => string
                            [width] => m
                            [formItemProps] => Array
                                (
                                )

                        )

                )

        )

    [createForm] => Array
        (
            [properties] => Array
                (
                    [0] => Array
                        (
                            [title] => Firstname
                            [key] => firstname
                            [dataIndex] => firstname
                            [valueType] => string
                            [width] => m
                            [formItemProps] => Array
                                (
                                    [rules] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [required] => 1
                                                    [message] => Please enter a firstname
                                                )

                                        )

                                )

                        )

                    [1] => Array
                        (
                            [title] => Lastname
                            [key] => lastname
                            [dataIndex] => lastname
                            [valueType] => string
                            [width] => m
                            [formItemProps] => Array
                                (
                                )

                        )

                )

        )

    [itemActions] => Array
        (
            [edit] => Array
                (
                    [type] => form
                    [key] => editForm
                    [title] => edit
                )

            [delete] => Array
                (
                    [type] => form
                    [key] => deleteForm
                    [title] => delete
                )

        )

)
```


## Contributing :purple_heart:
Please ...
1. ... register an account at https://git.fluxlabs.ch
2. ... create pull requests :fire:


## Adjustment suggestions / bug reporting :feet:
Please ...
1. ... register an account at https://git.fluxlabs.ch
2. ... ask us for a Service Level Agreement: support@fluxlabs.ch :kissing_heart:
3. ... read and create issues