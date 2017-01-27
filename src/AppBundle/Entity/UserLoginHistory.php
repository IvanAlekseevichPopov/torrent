<?php

/**
 * Ценоанализатор
 *
 * @author Попов Иван
 * @link   http://ценоанализатор.рф
 */

declare(strict_types = 1);

namespace AppBundle\Entity;

use AppBundle\Traits\Doctrine as DoctrineHelpersTrait;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JmsAnnotation;

/**
 * User
 *
 * @ORM\Table(
 *     name="users_login_history",
 *     options={
 *          "collate"="utf8mb4_unicode_ci",
 *          "charset"="utf8mb4"
 *      }
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Попов Иван
 * @link   http://ценоанализатор.рф
 */
class UserLoginHistory
{
    use DoctrineHelpersTrait\LifecycleCallbacks\CreatedAtLifecycleTrait;

    /**
     * Порядковый номер
     *
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     options={
     *          "comment" = "Id пользователя"
     *     }
     * )
     * @ORM\Id
     */
    protected $id;

    /**
     * @var \AppBundle\Entity\User
     *
     * @JMSAnnotation\Type("AppBundle\Entity\Users\User")
     * @JMSAnnotation\Since("1.0")
     *
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\User"
     * )
     *
     * @ORM\JoinColumns(
     *      @ORM\JoinColumn(
     *          name="user_id",
     *          referencedColumnName="id"
     *      )
     * )
     */
    protected $user;

    /**
     * IP клиента
     *
     * @JmsAnnotation\Type("string")
     * @JmsAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="client_ip",
     *     type="IpType",
     *     nullable=true,
     *     options={
     *         "comment" = "IP клиента"
     *     }
     * )
     *
     * @var string
     */
    protected $clientIp;

    /**
     * Сеттер IP адреса клиента
     *
     * @param string $clientIp
     *
     * @return $this
     */
    public function setClientIp(string $clientIp)
    {
        $this->clientIp = $clientIp;

        return $this;
    }

    /**
     * Геттер IP адреса клиента
     *
     * @return string
     */
    public function getClientIp(): string
    {
        return (string)$this->clientIp;
    }
}
