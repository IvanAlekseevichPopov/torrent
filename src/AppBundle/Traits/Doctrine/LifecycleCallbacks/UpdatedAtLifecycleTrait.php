<?php

/**
 * Ценоанализатор
 *
 * @author Попов Иван
 * @link   http://ценоанализатор.рф
 */

declare(strict_types = 1);

namespace AppBundle\Traits\Doctrine\LifecycleCallbacks;

/**
 *
 *
 * @author Попов Иван
 * @link   http://ценоанализатор.рф
 */
trait UpdatedAtLifecycleTrait
{
    /**
     * Установка даты обновления
     *
     * @Doctrine\ORM\Mapping\PreFlush
     *
     * @return $this
     */
    final public function setUpdatedAtValue()
    {
        if(method_exists($this, 'setUpdatedAt'))
        {
            return $this->setUpdatedAt(new \DateTime('NOW'));
        }

        return $this;
    }
}
