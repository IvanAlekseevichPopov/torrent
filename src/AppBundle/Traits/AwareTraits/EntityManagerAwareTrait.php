<?php

namespace AppBundle\Traits\AwareTraits;

use Doctrine\ORM\EntityManager;

/**
 *
 *
 * @author Попов Иван
 */
trait EntityManagerAwareTrait
{
    /** @var EntityManager|null */
    private $entityManager;

    /**
     * Геттер EntityManager
     *
     * @return EntityManager|null
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Сеттер EntityManager
     *
     * @param EntityManager $entityManager
     *
     * @return $this
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }
}
