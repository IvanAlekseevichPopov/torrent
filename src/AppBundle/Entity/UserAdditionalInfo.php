<?php

/**
 * Ценоанализатор
 *
 * @author Масюкевич Максим (Desperado)
 * @link   http://ценоанализатор.рф
 */

declare(strict_types = 1);

namespace AppBundle\Entity;

use AppBundle\Traits\Doctrine as DoctrineHelpersTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JmsAnnotation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserAdditionalInfo
 *
 * @ORM\Table(
 *     name="users_additional_info",
 *     options={
 *          "collate"="utf8mb4_unicode_ci",
 *          "charset"="utf8mb4"
 *      },
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Users\UserAdditionalInfoRepository")
 *
 * @author Масюкевич Максим (Desperado)
 * @link   http://ценоанализатор.рф
 */
class UserAdditionalInfo
{
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
     * Пользователь
     *
     * @ORM\OneToOne(
     *     targetEntity="User",
     *     inversedBy="additionalInfo",
     *     fetch="EXTRA_LAZY"
     * )
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(
     *       name="user_id",
     *       referencedColumnName="id"
     *     )
     * })
     *
     * @var User
     */
    protected $user;

    /**
     * Дата рождения
     *
     * @JMSAnnotation\Type("DateTime")
     * @JMSAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="dt_birthday",
     *     type="date",
     *     nullable=true
     * )
     *
     * @var DateTime|null
     */
    protected $birthday;

    /**
     * Кол-во детей
     *
     * @JMSAnnotation\Type("integer")
     * @JMSAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="child_quantity",
     *     type="UserAdditionalChildStatusEnumType",
     *     nullable=true,
     *     options={
     *         "comment" = "Кол-во детей"
     *     }
     * )
     *
     * @var integer
     */
    protected $childQuantity;

    /**
     * Пол
     *
     * @JMSAnnotation\Type("integer")
     * @JMSAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="gender",
     *     type="UserAdditionalGenderEnumType",
     *     nullable=true,
     *     options={
     *          "comment" = "Пол"
     *     }
     * )
     *
     * @var integer
     */
    protected $gender;

    /**
     * Семейное положение
     *
     * @JMSAnnotation\Type("integer")
     * @JMSAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="marital_status",
     *     type="UserAdditionalMaritalStatusEnumType",
     *     nullable=true,
     *     options={
     *          "comment":"Семейное положение"
     *     }
     * )
     *
     * @var integer
     */
    protected $maritalStatus;

    /**
     * Занятость
     *
     * @JMSAnnotation\Type("integer")
     * @JMSAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="busyness",
     *     type="UserAdditionalBusyStatusEnumType",
     *     nullable=true,
     *     options={
     *          "comment" = "Занятость"
     *     }
     * )
     *
     * @var integer
     */
    protected $busyness;

    /**
     * Сеттер даты рождения
     *
     * @param DateTime|null $birthday
     *
     * @return $this
     */
    public function setBirthday(DateTime $birthday = null)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Сеттер кол-ва детей
     *
     * @param integer|null $quantity
     *
     * @return $this
     */
    public function setChildQuantity($quantity)
    {
        $this->childQuantity = $quantity;

        return $this;
    }

    /**
     * Сеттер пола
     *
     * @param integer|null $gender
     *
     * @return $this
     */
    public function setGender(int $gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Сеттер семейного положения
     *
     * @param integer|null $status
     *
     * @return $this
     */
    public function setMaritalStatus(int $status)
    {
        $this->maritalStatus = $status;

        return $this;
    }

    /**
     * Сеттер трудоустроенности
     *
     * @param integer|null $busyness
     *
     * @return $this
     */
    public function setBusyness(int $busyness)
    {
        $this->busyness = $busyness;

        return $this;
    }

    /**
     * Геттер даты рождения
     *
     * @return DateTime|null
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Геттер кол-ва детей
     *
     * @return integer|null
     */
    public function getChildQuantity()
    {
        return $this->childQuantity;
    }

    /**
     * Геттер пола
     *
     * @return integer|null
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Геттер семейного положения
     *
     * @return integer|null
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * Геттер рода занятий
     *
     * @return integer|null
     */
    public function getBusyness()
    {
        return $this->busyness;
    }
}
