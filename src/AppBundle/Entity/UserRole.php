<?php

declare(strict_types = 1);

namespace AppBundle\Entity;

use AppBundle\Traits\Doctrine as DoctrineHelpersTrait;
use JMS\Serializer\Annotation as JmsAnnotation;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * @ORM\Table(
 *     name="users_roles",
 *     options={
 *          "collate"="utf8mb4_unicode_ci",
 *          "charset"="utf8mb4"
 *     },
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="u_r_name",
 *             columns={
 *                 "name"
 *             }
 *         )
 *     }
 * )
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRoleRepository")
 *
 * @author Попов Иван
 */
class UserRole implements RoleInterface
{
    const ROLE_USER      = 'ROLE_USER';
    const ROLE_MODERATOR = 'ROLE_MODERATOR';
    const ROLE_ADMIN     = 'ROLE_ADMIN';


    /**
     * Порядковый номер
     *
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     options={
     *          "comment" = "Id роли"
     *     }
     * )
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    protected $id;

    /**
     * Название роли
     *
     * @JMSAnnotation\Type("string")
     * @JMSAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="name",
     *     type="string",
     *     nullable=false,
     *     length = 64,
     *     options={
     *         "comment" = "Название роли",
     *         "fixed" = true
     *     }
     * )
     *
     * @var string
     */
    protected $name;

    /**
     * Описание
     *
     * @ORM\Column(
     *     name="description",
     *     type="string",
     *     nullable=true,
     *     options={
     *         "comment" = "Описание"
     *     }
     * )
     *
     * @var string
     */
    protected $description;

    /**
     * Родительская роль
     *
     * @ORM\ManyToOne(
     *     targetEntity="UserRole",
     *     cascade={
     *         "remove"
     *     }
     * )
     * @ORM\JoinColumn(
     *     name="parent_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     * )
     *
     * @var UserRole
     */
    protected $parent;

    /**
     * Сеттер названия роли
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Сеттер родительской роли
     *
     * @param UserRole $parent
     *
     * @return $this
     */
    public function setParent(UserRole $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Сеттер описания
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = '' !== $description ? $description : null;

        return $this;
    }

    /**
     * Геттер описания
     *
     * @return string
     */
    public function getDescription(): string
    {
        return (string) $this->description;
    }

    /**
     * Геттер родительской роли
     *
     * @return UserRole
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Геттер названия роли
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getRole(): string
    {
        return $this->getName();
    }

    /**
     * Имеется ли родительская роль
     *
     * @return boolean
     */
    public function hasParent(): bool
    {
        return null !== $this->getParent();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return (string)$this->getName();
    }
}
