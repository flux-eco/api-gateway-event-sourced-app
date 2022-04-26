<?php

namespace FluxEco\ApiGatewayEventSourcedApp\Core\Application\QueryHandlers;

use FluxEco\ApiGatewayEventSourcedApp\Core\{Application, Domain, Ports};

/**
 * @author martin@fluxlabs.ch
 */
class GetItemListHandler implements QueryHandler
{
    private string $getItemListHandler;
    private Ports\Outbounds $outbounds;

    private function __construct(
        string $getItemListHandler,
        Ports\Outbounds $outbounds
    ) {
        $this->getItemListHandler = $getItemListHandler;
        $this->outbounds = $outbounds;
    }

    public static function new(
        $getItemOperationName,
        Ports\Outbounds $outbounds
    ) : self {
        return new self(
            $getItemOperationName,
            $outbounds
        );
    }

    public function handle(Query $query, array $nextHandlers) : array
    {
        if ($query->getOperationName() !== $this->getItemListHandler) {
            return $this->process($query, $nextHandlers);
        }

        //todo
        $queryParameter['parentId'] = null;
        $queryParameter['offset'] = null;
        $queryParameter['limit'] = null;
        $queryParameter['orderBy'] = null;
        $queryParameter['search'] = null;

        //todo
        $requestContent = $query->getRequestContent();
        if(in_array('descend', $requestContent)) {
            $key = array_search('descend', $requestContent);
            $queryParameter['orderBy'] = [$key => 'descend'];
        }
        if(in_array('ascend', $requestContent)) {
            $key = array_search('ascend', $requestContent);
            $queryParameter['orderBy'] = [$key => 'ascend'];
        }

        $parameter = array_merge($queryParameter, $query->getRequestContent());


        $items = $this->outbounds->getProjectedItemList($query->getProjectionName(), $parameter);

        $parameter['limit'] = null;
        $queryParameter['offset'] = null;
        $total = count($this->outbounds->getProjectedItemList($query->getProjectionName(), $parameter));
        return ['data' => $items, 'success' => true, 'total' => $total];
    }

    public function process(Query $query, array $nextHandlers) : array
    {
        $nextHandler = $nextHandlers[0];

        unset($nextHandlers[0]); // remove item at index 0
        $nextHandlers = array_values($nextHandlers); // 'reindex' array

        return $nextHandler->handle($query, $nextHandlers);
    }
}