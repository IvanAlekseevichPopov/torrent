<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\{
    ContainerAwareInterface, ContainerAwareTrait, ContainerInterface
};

abstract class AppManagerAbstract implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** @var EntityManager|null */
    private $entityManager;
    /** @var \Redis */
    private $redisStorage;

    /**
     * @param EntityManager $entityManager
     * @param ContainerInterface $container
     */
    public function __construct(
        EntityManager $entityManager, ContainerInterface $container
    )
    {
        $this->setEntityManager($entityManager);
        $this->setContainer($container);
        //TODO проверяем наличие редиса и подключаем динамически(т.е. наличие необязательно пока)
    }

    /**
     * Добавление объекта в стек наблюдения
     *
     * @param object $entity
     *
     * @return $this
     */
    public function persist($entity)
    {
        $this->getEntityManager()->persist($entity);

        return $this;
    }

    /** @return EntityManager */
    public function getEntityManager()
    {
        $this->reopen();

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

    /**
     * Открываем EntityManager в случае проблем
     *
     * @return $this
     */
    public function reopen()
    {
        if (false === $this->entityManager->isOpen()) {
            $this->entityManager = EntityManager::create(
                $this->entityManager->getConnection(),
                $this->entityManager->getConfiguration(),
                $this->entityManager->getEventManager()
            );
        }

        return $this;
    }

    /**
     * Сохранение объекта
     *
     * @param object|null $entity
     *
     * @return $this
     */
    public function flush($entity = null)
    {
        if (null !== $entity) {
            $this->getEntityManager()->flush($entity);
        } else {
            $this->getEntityManager()->flush();
        }

        return $this;
    }

    /**
     * Удаление сущности из UnitOfWork
     *
     * @param object $entity
     *
     * @return $this
     */
    public function detach($entity)
    {
        $this->getEntityManager()->detach($entity);

        return $this;
    }

    /**
     * Удаление сущности
     *
     * @param object $entity
     *
     * @return $this
     */
    public function remove($entity)
    {
        $this->getEntityManager()->remove($entity);

        return $this;
    }

    /**
     * Старт транзакции
     *
     * @return $this
     */
    public function beginTransaction()
    {
        $this->getEntityManager()->beginTransaction();

        return $this;
    }

    /**
     * Коммит транзакции
     *
     * @return $this
     */
    public function commit()
    {
        $this->getEntityManager()->commit();

        return $this;
    }

    /**
     * Откат транзакции
     *
     * @return $this
     */
    public function rollback()
    {
        $this->getEntityManager()->rollback();

        return $this;
    }

    /**
     * Добавление объекта в стек наблюдения и сохранение
     *
     * @param object $entity
     *
     * @return void
     */
    public function persistAndSave($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    /**
     * Геттер репозитория
     *
     * @return EntityRepository;
     */
    abstract public function getRepository();

    /**
     * Геттер редиса
     *
     * @return \Redis
     */
    protected function getRedisStorage(): \Redis
    {
        return $this->redisStorage;
    }

    /**
     * Сеттер редиса
     *
     * @param \Redis $redisStorage
     *
     * @return $this
     */
    protected function setRedisStorage(\Redis $redisStorage)
    {
        $this->redisStorage = $redisStorage;

        return $this;
    }
}
