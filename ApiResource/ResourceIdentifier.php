<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

class ResourceIdentifier
{
    /**
     * Type
     * @var string
     */
    private $type;

    /**
     * Id
     * @var mixed
     */
    private $id;

    /**
     * Constructor
     * @param string $type Type
     * @param mixed  $id   Id
     */
    public function __construct($type, $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * Returns the json hash representation of this object
     * @return array Json hash
     */
    public function toJson()
    {
        return [
            'type' => $this->type,
            'id' => $this->id
        ];
    }

    /**
     * Get the value of Type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of Type
     * @param  string type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get the value of Id
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of Id
     * @param  mixed id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

}
