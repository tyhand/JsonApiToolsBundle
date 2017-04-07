<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

class Attribute
{
    /**
     * Name of the attribute
     * @var string
     */
    private $name;

    /**
     * Name of the attribute in json
     * @var string
     */
    private $jsonName;

    /**
     * Property linked to the attribute
     * @var string
     */
    private $property;

    /**
     * Entity whose property it is
     * @var string
     */
    private $entity;

    /**
     * Sortable
     * @var boolean
     */
    private $sortable;

    /**
     * Read Only
     * @var boolean
     */
    private $readOnly;

    /**
     * Formatter
     * @var Formatter
     */
    private $formatter;

    /**
     * Input Only
     * @var boolean
     */
    private $inputOnly;

    /**
     * Constructor
     * @param string $name Name of the attribute
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Add the attribute value to the json outpu
     * @param  mixed $entity Entity or entity collection
     * @param  array $json   Hash for json output
     * @return array         Altered json hash
     */
    public function addToJson($entity, array $json)
    {
        if (is_array($entity)) {
            if (array_key_exists($this->getEntity(), $entity)) {
                $json['attributes'][$this->getJsonName()] = $this->getFormatter()->toJson($entity[$this->getEntity()]->{'get' . ucfirst($this->property)}());
            }
        } else {
            $json['attributes'][$this->getJsonName()] = $this->getFormatter()->toJson($entity->{'get' . ucfirst($this->property)}());
        }

        return $json;
    }

    /**
     * Add the attribute back to the entity
     * @param  mixed $entity Entity or entity collection
     * @param  mixed $value  Value from json hash
     * @return mixed         Altered entity or entity collection
     */
    public function addToEntity($entity, $value)
    {
        if (!$this->readOnly) {
            if (is_array($entity)) {
                if (array_key_exists($this->getEntity(), $entity)) {
                    $entity[$this->getEntity()]->{'set' . ucfirst($this->property)}($this->getFormatter()->toEntity($value));
                }
            } else {
                $entity->{'set' . ucfirst($this->property)}($this->getFormatter()->toEntity($value));
            }
        }

        return $entity;
    }

    /**
     * Get the value of Name of the attribute
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Name of the attribute
     * @param string name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Get the value of Property linked to the attribute
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set the value of Property linked to the attribute
     * @param string property
     * @return self
     */
    public function setProperty($property)
    {
        $this->property = $property;
        return $this;
    }

    /**
     * Get the value of Entity whose property it is
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set the value of Entity whose property it is
     * @param string entity
     * @return self
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Get the value of Sortable
     * @return boolean
     */
    public function getSortable()
    {
        return $this->sortable;
    }

    /**
     * Set the value of Sortable
     * @param boolean sortable
     * @return self
     */
    public function setSortable($sortable)
    {
        $this->sortable = $sortable;
        return $this;
    }

    /**
     * Get the value of Read Only
     * @return boolean
     */
    public function getReadOnly()
    {
        return $this->readOnly;
    }

    /**
     * Set the value of Read Only
     * @param boolean readOnly
     * @return self
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;
        return $this;
    }

    /**
     * Get the value of Formatter
     * @return Formatter
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * Set the value of Formatter
     * @param Formatter formatter
     * @return self
     */
    public function setFormatter(Formatter $formatter)
    {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * Get the value of Input Only
     * @return boolean
     */
    public function getInputOnly()
    {
        return $this->inputOnly;
    }

    /**
     * Set the value of Input Only
     * @param boolean inputOnly
     * @return self
     */
    public function setInputOnly($inputOnly)
    {
        $this->inputOnly = $inputOnly;
        return $this;
    }
}
