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
     * Constructor
     * @param Attribute $attribute  Attribute
     * @param string    $aliasChain Alias Chain
     */
    public function __construct(Attribute $attribute, $aliasChain)
    {
        $this->attribute = $attribute;
        $this->aliasChain = $aliasChain;
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
}
