<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

class HasManyRelationship extends Relationship
{
    // MODES
    const SET_MODE = 'set';
    const ADD_MODE = 'add';
    const REMOVE_MODE = 'remove';

    /**
     * Add Method
     * @var string
     */
    private $addMethod;

    /**
     * Remove Method
     * @var string
     */
    private $removeMethod;

    /**
     * How to handle changes to relations
     * @var string
     */
    private $mode = self::SET_MODE;

    /**
     * @{inheritDoc}
     */
    public function addToJson($entity, array $json, IncludeManager $includeManager = null)
    {
        if (is_array($entity)) {
            if (array_key_exists($this->getEntity(), $entity)) {
                $objects = $entity[$this->getEntity()]->{'get' . ucfirst($this->getProperty())}();
            }
        } else {
            $objects = $entity->{'get' . ucfirst($this->getProperty())}();
        }

        if (null !== $objects) {
            $relationJson = ['data' => []];
            foreach($objects as $object) {
                $identifier = new ResourceIdentifier($this->getResource(), $this->getIdForRelatedEntity($object));
                if (null !== $includeManager) {
                    $includeManager->addResourceIdentifier($this->getName(), $identifier);
                }

                $relationJson['data'][] = $identifier->toJson();
            }
            $json['relationships'][$this->getJsonName()] = $relationJson;
        }

        return $json;
    }

    /**
     * @{inheritDoc}
     */
    public function addToEntity($entity, array $relationData, ResourceManager $manager)
    {
        if (is_array($entity)) {
            if (array_key_exists($this->getEntity(), $entity)) {
                $alteredEntity = $entity[$this->getEntity()];
            } else {
                throw new \Exception('Cannot match requested entity with entity in map');
            }
        } else {
            $alteredEntity = $entity;
        }

        // Check that the data key is present
        if (array_key_exists('data', $relationData)) {
            // Since this is a full replacement, get the existing first
            $original = $alteredEntity->{'get' . ucfirst($this->getProperty())}();
            if (self::SET_MODE === $this->mode) {
                foreach($original as $item) {
                    $alteredEntity->{$this->removeMethod}($item);
                }
            }

            // Check if individual reference object or array or reference objects
            $identifiers = [];
            if (0 < count($relationData['data'])) {
                if (is_array(reset($relationData['data']))) {
                    foreach($relationData['data'] as $data) {
                        if (isset($data['type']) && isset($data['id'])) {
                            $identifiers[] = new ResourceIdentifier($data['type'], $data['id']);
                        } else {
                            throw new \Exception('Identifier must have a type and an id');
                        }
                    }
                } else {
                    if (isset($relationData['data']['type']) && isset($relationData['data']['id'])) {
                        $identifiers[] = new ResourceIdentifier($relationData['data']['type'], $relationData['data']['id']);
                    } else {
                        throw new \Exception('Identifier must have a type and an id');
                    }
                }
            }

            foreach($identifiers as $identifier) {
                $loadedEntity = $manager->loadEntityFromIdentifier($identifier);
                if ($loadedEntity) {
                    if (self::REMOVE_MODE === $this->mode) {
                        $alteredEntity->{$this->removeMethod}($loadedEntity);
                    } else {
                        $alteredEntity->{$this->addMethod}($loadedEntity);
                    }
                }
            }
        }

        return $entity;
    }

    /**
     * @{inheritDoc}
     */
    public function getResourceIdentifierJson($entity)
    {
        if (is_array($entity)) {
            if (array_key_exists($this->getEntity(), $entity)) {
                $objects = $entity[$this->getEntity()]->{'get' . ucfirst($this->getProperty())}();
            }
        } else {
            $objects = $entity->{'get' . ucfirst($this->getProperty())}();
        }

        $json = [];
        foreach($objects as $object) {
            $json[] = [
                'type' => $this->getResource(),
                'id' => $this->getIdForRelatedEntity($object)
            ];
        }

        return $json;
    }

    /**
     * Handles the relationships by replacement
     * @return self
     */
    public function setModeToSet()
    {
        $this->mode = self::SET_MODE;
        return $this;
    }

    /**
     * Handles the relationships by adding to them
     * @return self
     */
    public function setModeToAdd()
    {
        $this->mode = self::ADD_MODE;
        return $this;
    }

    /**
     * Handles the relationships by removing
     * @return self
     */
    public function setModeToRemove()
    {
        $this->mode = self::REMOVE_MODE;
        return $this;
    }

    /**
     * Get the value of Add Method
     * @return string
     */
    public function getAddMethod()
    {
        return $this->addMethod;
    }

    /**
     * Set the value of Add Method
     * @param string addMethod
     * @return self
     */
    public function setAddMethod($addMethod)
    {
        $this->addMethod = $addMethod;
        return $this;
    }

    /**
     * Get the value of Remove Method
     * @return string
     */
    public function getRemoveMethod()
    {
        return $this->removeMethod;
    }

    /**
     * Set the value of Remove Method
     * @param string removeMethod
     * @return self
     */
    public function setRemoveMethod($removeMethod)
    {
        $this->removeMethod = $removeMethod;
        return $this;
    }

    /**
     * Get the value of Set Method
     * @return string
     */
    public function getSetMethod()
    {
        return $this->setMethod;
    }

    /**
     * Set the value of Set Method
     * @param string setMethod
     * @return self
     */
    public function setSetMethod($setMethod)
    {
        $this->setMethod = $setMethod;
        return $this;
    }

}
