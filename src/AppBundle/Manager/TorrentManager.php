<?php

declare(strict_types = 1);

namespace AppBundle\Manager;

use AppBundle\Entity\Torrent;
use AppBundle\Manager\AppManagerAbstract;
use Monolog\Logger;

class TorrentManager extends AppManagerAbstract
{
//    /**
//     * @param EncoderFactoryInterface $passwordEncoder
//     * @param EntityManager           $entityManager
//     * @param \Redis                  $redisStorage
//     * @param ContainerInterface      $container
//     */
//    public function __construct(
//        EncoderFactoryInterface $passwordEncoder, EntityManager $entityManager, \Redis $redisStorage,
//        ContainerInterface $container
//    )
//    {
//        $this->passwordEncoder = $passwordEncoder;
//
//        parent::__construct($entityManager, $redisStorage, $container);
//    }

    public function getTorrentsList($filters)
    {
        //TODO поиск здесь или отдельно - обмозговать
        dump($filters);
        return [];
    }

    /**
     * @inheritdoc
     *
     */
    public function getRepository()
    {
        return $this->getEntityManager()->getRepository(Torrent::class);
    }

    /**
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     *
     * @return Logger
     */
    private function getLogger()
    {
        return $this->container->get('logger');
    }
}
