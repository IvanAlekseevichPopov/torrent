<?php

declare(strict_types=1);

namespace AppBundle\Services;

class UniqueTokenGenerator
{
//    protected $container;
//
//    public function __construct(ContainerInterface $container)
//    {
//        $this->container = $container;
//    }

    /**
     * Генератор уникального токена
     *
     * @param int $count
     * @return string
     */
    public function getUniqueToken(int $count = 16): string
    {
        return bin2hex(openssl_random_pseudo_bytes($count));
    }

}