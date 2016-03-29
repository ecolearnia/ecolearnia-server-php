<?php
namespace App\Ecofy\Support;

/**
 * Helper class that Builds Eloquent query from criteria.
 */
class QueryBuilderEloquent {
    /**
     * @param array $criteria criteria in EcoCriteria AST
     * @param QueryBuilder $query an empty Eloquent Query object
     */
    public function buildQuery($criteria, &$query)
    {
		if (!empty($criteria)) {
			$childQuery = $this->buildQueryRecursive($criteria, $query);
            $query->addNestedWhereQuery($childQuery);
		}
        return $query;
    }

    function buildQueryRecursive(&$criteria, &$parentQuery)
    {
        $query = $parentQuery->newQuery();
        $query->from($parentQuery->from);

        // The only two operatos that has children
        $op = $criteria['op'];
        if ($op == 'or' || $op == 'and')
        {
            foreach($criteria['args'] as $childCriteria) {
                $childQuery = $this->buildQueryRecursive($childCriteria, $query);
                $query->addNestedWhereQuery($childQuery, $op);
            }
        }
        else if ($op == 'in' || $op == 'nin') {
            if ($op == 'in') {
                $query->whereIn($criteria['var'], $criteria['args']);
            } else {
                $query->whereNotIn($criteria['var'], $criteria['args']);
            }
        }
        else if ($op == 'between') {
            $range = [
                $criteria['args']['from'], $criteria['args']['to']
            ];
            $query->whereBetween($criteria['var'], $range);
        }
        else {
            // The rest of comp operator
            //print('**** var=' . $criteria['var'] . ' ' . $op . ' ' . $criteria['val']);
            $query->where($criteria['var'], $op, $criteria['val']);
        }
        return $query;
    }
}
