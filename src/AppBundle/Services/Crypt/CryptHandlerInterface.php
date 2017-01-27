<?php

/**
 * Ценоанализатор
 *
 * @author Масюкевич Максим (Desperado)
 * @link   http://ценоанализатор.рф
 */

declare(strict_types = 1);

namespace AppBundle\Services\Crypt;

/**
 * Интерфейс хэндлеров криптования/декриптования контента
 *
 * @author Масюкевич Максим (Desperado)
 * @link   http://ценоанализатор.рф
 */
interface CryptHandlerInterface
{
    /**
     * Криптование контента
     *
     * @param string $content
     *
     * @return string
     */
    public function encrypt($content);

    /**
     * Декриптование контента
     *
     * @param string $content
     *
     * @return string
     */
    public function decrypt($content);
}
