<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

class HasOneRelationship extends Relationship
{
    /**
     * @{inheritDoc}
     */
    public function addToJson($entity, array $json, IncludeManager $includeManager = null)
    {
        if (is_array($entity)) {
            if (array_key_exists($this->getEntity(), $entity)) {
                $object = $entity[$this->getEntity()]->{'get' . ucfirst($this->getProperty())}();
            }
        } else {
            $object = $entity->{'get' . ucfirst($this->getProperty())}();
        }

        if (null !== $object) {
            $identifier = new ResourceIdentifier($this->getResource(), $this->getIdForRelatedEntity($object));
            if (null !== $includeManager) {
                $includeManager->addResourceIdentifier($this->getName(), $identifier);
            }

            $json['relationships'][$this->getJsonName()] = ['data' => $identifier->toJson()];
        } else {
            $json['relationships'][$this->getJsonName()] = ['data' => null];
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
            // Check if individual reference object or array or reference objects
            if (!is_array(reset($relationData['data']))) {
                if (isset($relationData['data']['type']) && isset($relationData['data']['id'])) {
                    $identifier = new ResourceIdentifier($relationData['data']['type'], $relationData['data']['id']);
                } else {
                    $identifier = null;
                }
            } else {
                throw new \Exception('Not a has many relationship');
            }

            if ($identifier) {
                $loadedEntity = $manager->loadEntityFromIdentifier($identifier);
            } else {
                $loadedEntity = null;
            }
            if ($loadedEntity) {
                $alteredEntity->{'set' . ucfirst($this->getProperty())}($loadedEntity);
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
                $object = $entity[$this->getEntity()]->{'get' . ucfirst($this->getProperty())}();
            }
        } else {
            $object = $entity->{'get' . ucfirst($this->getProperty())}();
        }

        return [
            'type' => $this->getResource(),
            'id' => $this->getIdForRelatedEntity($object)
        ];
    }
}
