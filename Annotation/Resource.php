<?php

namespace TyHand\JsonApiToolsBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Resource extends Annotation
{
    /**
     * @var string
     *
     * @Required
     */
    public $entity;

    /**
     * Allow the resource to be deleted
     * @var boolean
     */
    public $allowDelete = true;

    /**
     * Run the symfony validator during the validation phase
     * @var boolean
     */
    public $runSymfonyValidator = true;

    /**
     * Whether to use symfony voters in the default controller actions
     * @var boolean
     */
    public $useVoters = false;

    /**
     * Voter view attribute
     * @var string
     */
    public $voterViewAttribute = 'view';

    /**
     * Voter create attribute
     * @var string
     */
    public $voterCreateAttribute = 'create';

    /**
     * Voter edit attribute
     * @var string
     */
    public $voterEditAttribute = 'edit';

    /**
     * Voter delete attribute
     * @var string
     */
    public $voterDeleteAttribute = 'delete';

    /**
     * Get the backing entity (or entities seperated by comma)
     * @return string Entity (or comma seperated entities)
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Allow delete
     * @return boolean
     */
    public function getAllowDelete()
    {
        return $this->allowDelete;
    }

    /**
     * Whether to run the symfony validator
     * @return boolean
     */
    public function getRunSymfonyValidator()
    {
        return $this->runSymfonyValidator;
    }

    /**
     * Get the value of Whether to use symfony voters in the default controller actions
     * @return boolean
     */
    public function getUseVoters()
    {
        return $this->useVoters;
    }

    /**
     * Get the value of Voter view attribute
     * @return string
     */
    public function getVoterViewAttribute()
    {
        return $this->voterViewAttribute;
    }

    /**
     * Get the value of Voter create attribute
     * @return string
     */
    public function getVoterCreateAttribute()
    {
        return $this->voterCreateAttribute;
    }

    /**
     * Get the value of Voter edit attribute
     * @return string
     */
    public function getVoterEditAttribute()
    {
        return $this->voterEditAttribute;
    }
 
    /**
     * Get the value of Voter delete attribute
     * @return string
     */
    public function getVoterDeleteAttribute()
    {
        return $this->voterDeleteAttribute;
    }
}
