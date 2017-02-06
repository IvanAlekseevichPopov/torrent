<?php

declare(strict_types = 1);

namespace AppBundle\Traits\Doctrine;

use Doctrine\ORM\Mapping\PrePersist;
use JMS\Serializer\Annotation as JmsAnnotation;

trait CreatedAtColumn
{

    /**
     * Дата создания
     *
     * @JmsAnnotation\Type("DateTime<'Y-m-d H:i:s'>")
     * @JmsAnnotation\Since("1.0")
     *
     * @Doctrine\ORM\Mapping\Column(
     *     name="created_at",
     *     type="datetime",
     *     nullable=false,
     *     options={
     *         "comment" = "Дата создания"
     *     }
     * )
     *
     * @var \DateTime
     */
    protected $createdAt;
    /**
     * Сеттер даты создания
     *
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Геттер даты создания
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return null !== $this->createdAt ? $this->createdAt : new \DateTime('NOW');
    }

    /**
     * Установка даты создания
     *
     * @PrePersist
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
