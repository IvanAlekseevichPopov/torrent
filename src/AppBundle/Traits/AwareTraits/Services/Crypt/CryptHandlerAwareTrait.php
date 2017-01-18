<?php

declare(strict_types = 1);

namespace AppBundle\Traits\AwareTraits\Services\Crypt;

use AppBundle\Services\Crypt\CryptHandlerInterface;

/**
 *
 *
 * @author Попов Иван
 */
trait CryptHandlerAwareTrait
{
    /** @var CryptHandlerInterface */
    private $cryptHandler;

    /**
     * Сеттер хэндлера криптования/декриптования контента
     *
     * @param CryptHandlerInterface $cryptHandler
     *
     * @return $this
     */
    public function setCryptHandler(CryptHandlerInterface $cryptHandler)
    {
        $this->cryptHandler = $cryptHandler;

        return $this;
    }

    /**
     * Геттер хэндлера криптования/декриптования контента
     *
     * @return CryptHandlerInterface
     */
    public function getCryptHandler()
    {
        return $this->cryptHandler;
    }
}
