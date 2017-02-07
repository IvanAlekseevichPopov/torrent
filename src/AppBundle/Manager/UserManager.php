<?php

declare(strict_types = 1);

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;

class UserManager extends AppManagerAbstract
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
//
//    public function getTorrentsList($filters)
//    {
//        //TODO поиск здесь или отдельно - обмозговать
//        dump($filters);
//
//        //TODO получаем offset из фильтра limit - захардкодить в константу
//        $limit = 10;
//        $offset = 0;
//        return
//            $this->getRepository()->findBy(
//                [],
//                ['createdAt' => 'DESC'],
//                $limit,
//                $offset
//            );
//    }

    public function findUserByEmail(string $email)
    {
        //TODO
        dump($email);
        return null;
    }

    /**
     * @return UserRepository
     */
    public function getRepository(): UserRepository
    {
        return $this->getEntityManager()->getRepository(User::class);
    }
}
