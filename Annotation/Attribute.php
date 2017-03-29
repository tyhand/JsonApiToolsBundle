<?php

namespace TyHand\JsonApiToolsBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Attribute extends Annotation
{
    /**
     * Optional entity if resource is a combination
     * @var string
     */
    public $entity;

    /**
     * Property name
     * @var string
     */
    public $property;

    /**
     * Name the attribute takes in json
     * @var string
     */
    public $jsonName;

    /**
     * Name of the formatter to use
     * @var string
     */
    public $formatter = 'default';

    /**
     * Read only
     * @var boolean
     */
    public $readOnly = false;

    /**
     * Sortable
     * @var boolean;
     */
    public $sortable = true;

    /**
     * Input Only
     * @var boolean
     */
    public $inputOnly = false;

    /**
     * Get the entity if it exists
     * @return string Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Get the property name
     * @return string Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Get the json name
     * @return string Json Name
     */
    public function getJsonName()
    {
        return $this->jsonName;
    }

    /**
     * Get the value of Name of the formatter to use
     * @return string
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * Get the value of Read only
     * @return boolean
     */
    public function getReadOnly()
    {
        return $this->readOnly;
    }

    /**
     * Sortable
     * @return boolean Sortable
     */
    public function getSortable()
    {
        return $this->sortable;
    }

    /**
     * Get the value of Input Only
     * @return boolean
     */
    public function getInputOnly()
    {
        return $this->inputOnly;
    }

}
