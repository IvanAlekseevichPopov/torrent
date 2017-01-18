<?php

namespace AppBundle\Traits\AwareTraits;

/**
 *
 *
 * @author Попов Иван
 */
trait RedisStorageAwareTrait
{
    /** @var \Redis */
    private $redisStorage;

    /**
     * Геттер редиса
     *
     * @return \Redis
     */
    public function getRedisStorage(): \Redis
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
    public function setRedisStorage(\Redis $redisStorage)
    {
        $this->redisStorage = $redisStorage;

        return $this;
    }
}
