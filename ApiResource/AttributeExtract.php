<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

/**
 * Small helper class for the join manager and sorting
 */
class AttributeExtract
{
    /**
     * Attribute
     * @var Attribute
     */
    private $attribute;

    /**
     * Alias Chain
     * @var string
     */
    private $aliasChain;

    /**
     * Property Chain
     * @var string
     */
    private $propertyChain;

    /**
     * Constructor
     * @param Attribute $attribute     Attribute
     * @param string    $aliasChain    Alias Chain
     * @param string    $propertyChain Property Chain
     */
    public function __construct(Attribute $attribute, $aliasChain, $propertyChain)
    {
        $this->attribute = $attribute;
        $this->aliasChain = $aliasChain;
        $this->propertyChain = $propertyChain;
    }

    /**
     * Get the value of Attribute
     * @return Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Set the value of Attribute
     * @param Attribute attribute
     * @return self
     */
    public function setAttribute(Attribute $attribute)
    {
        $this->attribute = $attribute;
        return $this;
    }

    /**
     * Get the value of Alias Chain
     * @return string
     */
    public function getAliasChain()
    {
        return $this->aliasChain;
    }

    /**
     * Set the value of Alias Chain
     * @param string aliasChain
     * @return self
     */
    public function setAliasChain($aliasChain)
    {
        $this->aliasChain = $aliasChain;
        return $this;
    }

    /**
     * Get the value of Property Chain
     * @return string
     */
    public function getPropertyChain()
    {
        return $this->propertyChain;
    }

    /** 
     * Set the value of Property Chain
     * @param string propertyChain
     * @return self
     */
    public function setPropertyChain($propertyChain)
    {
        $this->propertyChain = $propertyChain;
        return $this;
    }
}
