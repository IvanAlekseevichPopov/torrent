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
trait CreatedAtLifecycleTrait
{
    /**
     * Установка даты создания
     *
     * @Doctrine\ORM\Mapping\PrePersist
     *
     * @return $this
     */
    final public function setCreatedAtValue()
    {
        if(method_exists($this, 'setCreatedAt'))
        {
            return $this->setCreatedAt(new \DateTime('NOW'));
        }

        return $this;
    }
}
