<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

abstract class Relationship
{
    /**
     * Name
     * @var string
     */
    private $name;

    /**
     * Json name
     * @var string
     */
    private $jsonName;

    /**
     * Name of the related resource
     * @var string
     */
    private $resource;

    /**
     * Property of the entity that handles the relationv
     *
     * @var string
     */
    private $property;

    /**
     * Name of the entity with the property if in a composite resource
     * @var string
     */
    private $entity;

    /**
     * Method to call on the relation to get the id
     * @var string
     */
    private $getIdMethod;

    /**
     * Whether this relationship can only be accessed through the relationships url
     * @var boolean
     */
    private $relationshipUrlOnly;

    /**
     * Constructor
     * @param string $name Name of the relation property
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Add relation to the json output
     * @param  mixed          $entity         Entity or entity collection
     * @param  array          $json           Json hash
     * @param  IncludeManager $includeManager Include Manager
     * @return array                          Altered json hash
     */
    public abstract function addToJson($entity, array $json, IncludeManager $includeManager = null);

    /**
     * Add relation back to the entity
     * @param  mixed          $entity       Entity being built
     * @param  array          $relationData Json hash of relationship data
     * @param  ResourceLoader $manager      Resource Manager
     * @return mixed                        Altered entity
     */
    public abstract function addToEntity($entity, array $relationData, ResourceManager $manager);

    /**
     * Get the Resource Identifier Objects Hash for the relationship
     * @param  mixed $entity Entity
     * @return array         RIO json
     */
    public abstract function getResourceIdentifierJson($entity);

    /**
     * Get the related objects from the entity for this resource
     * @param  mixed  $entity Entity for this relations resoruce
     * @return mixed          Entity or collection
     */
    public function getRelatedFromEntity($entity) {
        if (is_array($entity)) {
            if (array_key_exists($this->getEntity(), $entity)) {
                return $entity[$this->getEntity()]->{'get' . ucfirst($this->getProperty())}();
            }
        } else {
            return $entity->{'get' . ucfirst($this->getProperty())}();
        }
    }

    /**
     * Get the id for the related entity
     * @param  mixed  $entity Entity to get id for
     * @return mixed          Id
     */
    public function getIdForRelatedEntity($entity)
    {
        return $entity->{$this->getIdMethod}();
    }

    /**
     * Get the value of Name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Name
     * @param string property
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of Name of the related resource
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set the value of Name of the related resource
     * @param string resource
     * @return self
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * Get the value of Json Name of the attribute
     * @return string
     */
    public function getJsonName()
    {
        return $this->jsonName;
    }

    /**
     * Set the value of Json Name of the attribute
     * @param string name
     * @return self
     */
    public function setJsonName($jsonName)
    {
        $this->jsonName = $jsonName;
        return $this;
    }

    /**
     * Get the value of Property of the entity that handles the relation
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set the value of Property of the entity that handles the relation
     * @param string property
     * @return self
     */
    public function setProperty($property)
    {
        $this->property = $property;
        return $this;
    }

    /**
     * Get the value of Name of the entity with the property if in a composite resource
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set the value of Name of the entity with the property if in a composite resource
     * @param string entity
     * @return self
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Get the value of Method to call on the relation to get the id
     * @return string
     */
    public function getGetIdMethod()
    {
        return $this->getIdMethod;
    }

    /**
     * Set the value of Method to call on the relation to get the id
     * @param string getIdMethod
     * @return self
     */
    public function setGetIdMethod($getIdMethod)
    {
        $this->getIdMethod = $getIdMethod;
        return $this;
    }

    /**
     * Get the value of Whether this relationship can only be accessed through the relationships url
     * @return boolean
     */
    public function getRelationshipUrlOnly()
    {
        return $this->relationshipUrlOnly;
    }

    /**
     * Set the value of Whether this relationship can only be accessed through the relationships url
     * @param boolean relationshipUrlOnly
     * @return self
     */
    public function setRelationshipUrlOnly($relationshipUrlOnly)
    {
        $this->relationshipUrlOnly = $relationshipUrlOnly;
        return $this;
    }
}
