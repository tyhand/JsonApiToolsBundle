<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

use Doctrine\ORM\EntityManager;

class ResourceManager
{
    /**
     * @var ResourceReader
     */
    private $resourceReader;

    /**
     * @var EntityLoader
     */
    private $entityLoader;

    /**
     * Array of resources keyed by name
     * @var array
     */
    private $resources;

    /**
     * Constructor
     * @param ResourceReader $resourceReader Resource finder
     */
    public function __construct(ResourceReader $resourceReader, EntityLoader $entityLoader)
    {
        $this->resourceReader = $resourceReader;
        $this->entityLoader = $entityLoader;
        $this->resources = [];
    }

    /**
     * Load an entity from resource
     * @param  ResourceIdentifier $identifier Identifier
     * @return mixed                          Loaded entity
     */
    public function loadEntityFromIdentifier(ResourceIdentifier $identifier)
    {
        $resource = $this->getResource($identifier->getType());
        if (!$resource) {
            throw new \Exception('Type not found');
        }
        return $this->entityLoader->loadEntity($resource->getEntity(), $identifier->getId());
    }

    /**
     * Get the entity loader
     * @return EntityLoader Entity Loader
     */
    public function getEntityLoader()
    {
        return $this->entityLoader;
    }

    /**
     * Get a resource by name
     * @param  string $name Name of the resource to get
     * @return ApiResource Resource if exists
     */
    public function getResource($name)
    {
        if (array_key_exists($name, $this->resources)) {
            return $this->resources[$name];
        } elseif ($this->resourceReader->hasResource($name)) {
            $this->resources[$name] = $this->resourceReader->readResource($name);
            $this->resources[$name]->setManager($this);
            return $this->resources[$name];
        } else {
            throw new \Exception('Resource not found');
        }
    }
}
