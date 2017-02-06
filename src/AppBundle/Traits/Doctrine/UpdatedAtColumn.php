<?php

declare(strict_types = 1);

namespace AppBundle\Traits\Doctrine;

use Doctrine\ORM\Mapping\PreFlush;
use JMS\Serializer\Annotation as JmsAnnotation;

trait UpdatedAtColumn
{

    /**
     * Дата создания
     *
     * @JmsAnnotation\Type("DateTime<'Y-m-d H:i:s'>")
     * @JmsAnnotation\Since("1.0")
     *
     * @Doctrine\ORM\Mapping\Column(
     *     name="updated_at",
     *     type="datetime",
     *     nullable=false,
     *     options={
     *         "comment" = "Дата обновления"
     *     }
     * )
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * Сеттер даты обновления
     *
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Геттер даты обновления
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Установка даты обновления
     *
     * @PreFlush
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
