<?php

namespace TyHand\JsonApiToolsBundle\Extra;

use TyHand\JsonApiToolsBundle\Annotation\Filter;
use TyHand\JsonApiToolsBundle\ApiResource\Resource;

class SearchableResource extends Resource
{
    /**
     * @Filter(name="search")
     *
     * @param  string       $value        Search value
     * @param  string       $alias        Query builder alais
     * @param  QueryBuilder $queryBuilder Query builder
     * @param  JoinManager  $joins        Join Manager
     * @return QueryBuilder               Altered query builder
     */
    public function searchFilter($value, $alias, $queryBuilder, $joinManager)
    {
        foreach(explode(' ', $value) as $key => $searchString) {
            $orX = $queryBuilder->expr()->orX();
            foreach($this->getSearchableEntityFields() as $field) {
                $outer = false;
                if (is_array($field)) {
                    $fieldName = $field['property'];
                    if (isset($field['joinType']) && $field['joinType'] === 'outer') {
                        $outer = true;
                    }
                } else {
                    $fieldName = $field;
                }
                if (false === strpos($fieldName, '.')) {
                    $orX->add($queryBuilder->expr()->like('lower(' . $alias . '.' . $fieldName . ')', ':searchable_' . $key));
                } else {
                    $attributeExtract = $joinManager->extractAttribute($fieldName, $outer);
                    $orX->add($queryBuilder->expr()->like('lower(' . $attributeExtract->getPropertyChain() . '.' . $attributeExtract->getAttribute()->getProperty() . ')', ':searchable_' . $key));
                }
            }
            $queryBuilder->andWhere($orX);
            $queryBuilder->setParameter('searchable_' . $key, '%' . strtolower($searchString) . '%');
        }

        return $queryBuilder;
    }

    /**
     * Get the list of fields that are searchable
     * @return array Searchable fields
     */
    protected function getSearchableEntityFields()
    {
        return [];
    }
}
