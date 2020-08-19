<?php


namespace Ares\Framework\Helper;

use Ares\Framework\Exception\SearchCriteriaException;
use Ares\Framework\Model\SearchCriteria;
use ErrorException;

class SearchCriteriaHelper
{
    /**
     * Takes query and builds a SearchCriteria out of it.
     *
     * @param SearchCriteria $searchCriteria
     * @param array          $query
     *
     * @return SearchCriteria
     * @throws SearchCriteriaException
     */
    public function getSearchModelByQuery(SearchCriteria $searchCriteria, array $query): SearchCriteria
    {
        try {
            $term = $query['term'] ?: '';
            $page = $query['page'] ?: 0;
            $limit = $query['limit'] ?: 0;
            $offset = $query['offset'] ?: 0;

            $searchCriteria->setSearchTerm($term);
            $searchCriteria->setPage($page);
            $searchCriteria->setLimit($limit);
            $searchCriteria->setOffset($offset);

            if (!is_array($filters = $query['filters'])) {
                $filters = [];
            }

            if (!is_array($orders = $query['orders'])) {
                $orders = [];
            }

            foreach ($filters as $filter) {
                $searchCriteria->addFilter(
                    $filter['field'],
                    $filter['operator'],
                    $filter['value']
                );
            }

            foreach ($orders as $order) {
                $searchCriteria->addOrders(
                    $order['field'],
                    $order['direction']
                );
            }
        } catch (ErrorException $errorException) {
            throw new SearchCriteriaException(
                'Seems that the structure of the query is wrong or some filter/order sub-fields are missing.',
                $errorException->getCode()
            );
        }

        return $searchCriteria;
    }
}
