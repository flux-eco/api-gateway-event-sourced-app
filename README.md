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

            [1] => Array
                (
                    [projectionId] => 8bf4e997-ab7f-4082-8111-57a32c0e3693
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [2] => Array
                (
                    [projectionId] => b2b684cb-448c-483b-bcb4-38dbad3c9ab1
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [3] => Array
                (
                    [projectionId] => e262ea33-e051-43fc-8dcd-86e922c90dd4
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [4] => Array
                (
                    [projectionId] => a743a4d0-69b5-4b53-bf52-94800ed34207
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [5] => Array
                (
                    [projectionId] => e7b2461a-96f3-4fdf-b1e8-f67c2db5af97
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [6] => Array
                (
                    [projectionId] => 86c741d7-c281-4dc9-87cf-92ad5e930a57
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [7] => Array
                (
                    [projectionId] => 14471d68-4724-40e2-8d0a-7dbf45cc37f0
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [8] => Array
                (
                    [projectionId] => 0f6f54d8-9879-47a4-976e-ce5dc247472e
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [9] => Array
                (
                    [projectionId] => 7c010e6a-9db2-4309-a404-5ad15e672dfc
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [10] => Array
                (
                    [projectionId] => ddb4c42d-ab48-4790-900c-1504ff792341
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [11] => Array
                (
                    [projectionId] => be9c04a9-c6bd-4850-9c59-fd6ba5ebca58
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [12] => Array
                (
                    [projectionId] => 3e9de00d-5e76-4230-bbfa-8e54f1677cd7
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [13] => Array
                (
                    [projectionId] => f1eb56c4-25c2-45db-b527-c6416324120c
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [14] => Array
                (
                    [projectionId] => ede7ed65-e6a9-4014-8367-275e220b52ab
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [15] => Array
                (
                    [projectionId] => 972447ec-9622-4846-ba72-aa5babeb9c48
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [16] => Array
                (
                    [projectionId] => 6e4dd4fa-e381-4c90-89fb-f96421770836
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [17] => Array
                (
                    [projectionId] => 863d215f-ac01-417c-89f1-da25783ee18f
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [18] => Array
                (
                    [projectionId] => 6209a59e-1c93-4349-afbf-0a0224af5031
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [19] => Array
                (
                    [projectionId] => 0b547634-d606-4e57-a168-1ad0658e892d
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [20] => Array
                (
                    [projectionId] => 367d55bf-0abe-4d79-8ef6-20a51c8ae486
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [21] => Array
                (
                    [projectionId] => 6f2f9a8d-ca62-46ed-b7f3-bb8384e1c5b8
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [22] => Array
                (
                    [projectionId] => 11a6ae8b-f870-4593-9990-9cc0f99fa4fd
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [23] => Array
                (
                    [projectionId] => b6e92067-b95d-4adb-970c-72cd2ee4d23d
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [24] => Array
                (
                    [projectionId] => c38be9fe-c224-4b4f-9d4f-185d423a0299
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [25] => Array
                (
                    [projectionId] => 33878b6b-4ba2-4c82-a04d-b6a9d431e043
                    [firstname] => Emmett
                    [lastname] => Brown
                )

            [26] => Array
                (
                    [projectionId] => 4dd7b7ca-521e-4d64-9d78-c0a56eae985c
                    [firstname] => Emmett
                    [lastname] => Brown
                )

        )

    [status] => success
    [total] => 27
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