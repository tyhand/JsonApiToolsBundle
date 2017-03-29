<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

class Filter
{
    /**
     * Name of the filter
     * @var string
     */
    private $name;

    /**
     * Name of the filter method
     * @var string
     */
    private $method;

    /**
     * Constructor
     * @param string $method Name of the filter method
     */
    public function __construct($method)
    {
        $this->method = $method;
    }

    /**
     * Get the value of Name of the filter
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Name of the filter
     * @param string name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of Name of the filter method
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
 
    /**
     * Set the value of Name of the filter method
     * @param string method
     * @return self
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

}
