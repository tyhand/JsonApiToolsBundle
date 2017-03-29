<?php

namespace TyHand\JsonApiToolsBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class HasMany extends Annotation
{
    /**
     * Resource of the many side of the relation
     * @var string
     */
    public $resource;

    /**
     * Name of the relation as it appears in json
     * @var string
     */
    public $jsonName;

    /**
     * Property name
     * @var string
     */
    public $property;

    /**
     * Entity whose property is being pointed at if the resource is a composite
     * @var string
     */
    public $entity;

    /**
     * Method to add relation
     * @var string
     */
    public $addMethod;

    /**
     * Method to remove a relation
     * @var string
     */
    public $removeMethod;

    /**
     * Method to call on the entity to get the id
     * @var string
     */
    public $getIdMethod = 'getId';

    /**
     * Only allow this relationship to be accessed through the relationships url
     * @var boolean
     */
    public $relationshipUrlOnly = false;

    /**
     * Get the value of Resource of the many side of the relation
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Name of the relationship in json
     * @return string
     */
    public function getJsonName()
    {
        return $this->jsonName;
    }

    /**
     * Get the value of Property name
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Get the value of Entity whose property is being pointed at if the resource is a composite
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Get the value of Method to add relation
     * @return string
     */
    public function getAddMethod()
    {
        return $this->addMethod;
    }

    /**
     * Get the value of Method to remove a relation
     * @return string
     */
    public function getRemoveMethod()
    {
        return $this->removeMethod;
    }

    /**
     * Get the value of Method to set the whole list of relationships
     * @return string
     */
    public function getSetMethod()
    {
        return $this->setMethod;
    }

    /**
     * Get the getid method for the relationship
     * @return string
     */
    public function getGetIdMethod()
    {
        return $this->getIdMethod;
    }

    /**
     * Only allow this relationship to be accessed through the relationships url
     * @return boolean
     */
    public function getRelationshipUrlOnly()
    {
        return $this->relationshipUrlOnly;
    }
}
