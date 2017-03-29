<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

/**
 * Handles the include parameter
 */
class IncludeManager
{
    /**
     * Values from the include query parameter
     * @var array
     */
    private $includes;

    /**
     * Resource Manager
     * @var ResourceManager
     */
    private $manager;

    /**
     * Map of Identifiers
     * @var array
     */
    private $identifiers;

    /**
     * Constructor
     * @param ResourceManager $manager  Resource Manager
     * @param array           $includes Resources to include
     */
    public function __construct(ResourceManager $manager, $includes = [])
    {
        $this->includes = $includes;
        $this->manager = $manager;
        $this->identifiers = [];
    }

    /**
     * Add a resource identifier
     * @param  string             $relation
     * @param  ResourceIdentifier $identifier
     * @return self
     */
    public function addResourceIdentifier($relation, ResourceIdentifier $identifier)
    {
        if (in_array($relation, $this->includes)) {
            if (!array_key_exists($identifier->getType(), $this->identifiers)) {
                $this->identifiers[$identifier->getType()] = [];
            }

            if (!array_key_exists($identifier->getId(), $this->identifiers[$identifier->getType()])) {
                $this->identifiers[$identifier->getType()][$identifier->getId()] = $identifier;
            }
        }
    }

    /**
     * Returns true if there is data to be included
     * @return boolean Whether there is data here
     */
    public function hasData()
    {
        return (0 < count($this->identifiers));
    }

    /**
     * Convert to json
     * @return array Json Hash
     */
    public function toJson()
    {
        $json = [];

        foreach(array_keys($this->identifiers) as $type) {
            $resource = $this->manager->getResource($type);
            foreach($this->identifiers[$type] as $identifier) {
                $entity = $this->manager->loadEntityFromIdentifier($identifier);
                $json[] = $resource->toJson($entity);
            }
        }

        return $json;
    }
}
