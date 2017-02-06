<?php

declare(strict_types = 1);

namespace AppBundle\Manager;

use AppBundle\Entity\Torrent;
use AppBundle\Repository\TorrentRepository;
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

        //TODO получаем offset из фильтра limit - захардкодить в константу
        $limit = 10;
        $offset = 0;
        return
            $this->getRepository()->findBy(
                [],
                ['createdAt' => 'DESC'],
                $limit,
                $offset
            );
    }

    /**
     * @return TorrentRepository
     */
    public function getRepository(): TorrentRepository
    {
        return $this->getEntityManager()->getRepository(Torrent::class);
    }

//    /**
//     * Еще пригодится
//     * @return Logger
//     */
//    private function getLogger()
//    {
//        return $this->get('logger');
//    }
}
