<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

use TyHand\JsonApiToolsBundle\Util\Inflect;

/**
 * Simple util to help manage joins added to a query builder
 */
class JoinManager
{
    /**
     * Alias of the root
     * @var string
     */
    private $alias;

    /**
     * Resource root
     * @var string
     */
    private $rootResource;

    /**
     * Query builder
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * Resource Manager
     * @var ResourceManager
     */
    private $resourceManager;

    /**
     * Join Map
     * @var array
     */
    private $joins;

    /**
     * Hash of joined resources
     * @var array
     */
    private $resources;

    /**
     * Constructor
     */
    public function __construct($alias, Resource $rootResource, $queryBuilder, ResourceManager $manager)
    {
        $this->alias = $alias;
        $this->rootResource = $rootResource;
        $this->queryBuilder = $queryBuilder;
        $this->manager = $manager;

        $this->resources = [$this->alias => $this->rootResource];
        $this->joins = [];
    }

    /**
     * Extract attribute and process the required joins
     * @param  string    $name  Full name from the root resource
     * @param  boolean   $outer Perform an outer join instead of an inner
     * @return Attribute        Attribute
     */
    public function extractAttribute($name, $outer = false)
    {
        $parts = explode('.', $name);
        $resourceName = [];
        for($i = 0; $i < count($parts) - 1; $i++) {
            $resourceName[] = $parts[$i];
        }

        $aliasChain = implode('.', $resourceName);
        $resource = $this->joinResource($aliasChain, $outer);
        $attribute = $resource->getAttributeByJsonName($parts[count($parts) - 1]);

        return new AttributeExtract($attribute, $aliasChain);
    }

    /**
     * Join a resource
     * @param  string   $name  Name from root e.g. if bar is the root, and has foo as a relation this would just be foo.  If foo also has a relation called buzz then it will be foo.buzz
     * @param  boolean  $outer Perform an outer join instead of an inner
     * @return Resource        Resource
     */
    public function joinResource($name, $outer = false)
    {
        $parts = explode('.', $name);
        $currentName = [];
        $parentAlias = $this->alias;
        $parentResource = $this->rootResource;
        $mapPointer = &$this->joins;
        foreach($parts as $part) {
            $currentName[] = $part;
            if (array_key_exists($part, $mapPointer)) {
                $parentResource = $this->resources[implode('.', $currentName)];
            } else {
                // Get the relation from the parent
                $relation = $parentResource->getRelationshipByJsonName($part);
                if (!$relation) {
                    throw new \Exception('Relation not found');
                }

                if ($relation instanceof HasOneRelationship) {
                    $resource = $this->manager->getResource(Inflect::pluralize($relation->getName()));
                } else {
                    $resource = $this->manager->getResource($relation->getName());
                }

                if ($outer) {
                    $this->queryBuilder->leftJoin($parentAlias . '.' . $relation->getProperty(), $part);
                } else {
                    $this->queryBuilder->join($parentAlias . '.' . $relation->getProperty(), $part);
                }

                $this->resources[implode('.', $currentName)] = $resource;
                $mapPointer[$part] = [];
                $parentResource = $resource;
            }

            $parentAlias = $part;
            $mapPointer = &$mapPointer[$part];
        }

        return $parentResource;
    }
}
