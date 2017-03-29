<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

use Doctrine\ORM\EntityManager;

class EntityLoader
{
    /**
     * Entity Manager
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Constructor
     * @param EntityManager $entityManager Entity manager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Load entity
     * @param  string $entityName Name of the entity to load
     * @param  int    $id         Entity Id
     * @return mixed              Entities
     */
    public function loadEntity($entityName, $id)
    {
        return $this->entityManager->getRepository($entityName)->findOneById($id);
    }

    /**
     * Get the entity manager
     * @return EntityManager Entity Manager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
