<?php

declare(strict_types = 1);

namespace AppBundle\Entity;

use AppBundle\DBAL\Types\Enum\Users\UserStatusEnumType;
use AppBundle\Traits\Doctrine\LifecycleCallbacks\CreatedAtLifecycleTrait;
use AppBundle\Traits\Doctrine\LifecycleCallbacks\UpdatedAtLifecycleTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JmsAnnotation;
use AppBundle\Traits\Doctrine as DoctrineHelpersTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(
 *     name="users",
 *     options={
 *          "collate"="utf8mb4_unicode_ci",
 *          "charset"="utf8mb4"
 *      },
 *      uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="users_customer_email",
 *                 columns={
 *                     "email"
 *                 }
 *          )
 *     }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="email", message="Пользователь с указанным email уже сущствует")
 *
 */
class User implements UserInterface
{

    use CreatedAtLifecycleTrait;
    use UpdatedAtLifecycleTrait;

    const RESET_PASSWORD_LIFETIME_MODIFIER = '+24 hours';

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
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     * @var int
     *
     */
    protected $id;

    /**
     * Статус пользователя
     *
     * @JMSAnnotation\Type("integer")
     * @JMSAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="status_id",
     *     type="UserStatusEnumType",
     *     options={
     *          "comment" = "Статус пользователя"
     *     }
     * )
     *
     * @var integer
     */
    protected $statusId = UserStatusEnumType::STATUS_NOT_CONFIRMED;

    /**
     * Имя пользователя
     *
     * @JmsAnnotation\Type("string")
     * @JmsAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="user_name",
     *     type="string",
     *     nullable=false,
     *     length=255,
     *     options={
     *         "comment" = "Имя пользователя"
     *     }
     * )
     *
     * @var string
     */
    protected $userName;

    /**
     * Email
     *
     * @JmsAnnotation\Type("string")
     * @JmsAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="email",
     *     type="string",
     *     nullable=false,
     *     length=191,
     *     unique=true,
     *     options={
     *         "comment" = "Email"
     *     }
     * )
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @var string
     */
    protected $email;

    /**
     * Соль
     *
     * @JmsAnnotation\Exclude()
     *
     * @ORM\Column(
     *     name="salt",
     *     type="string",
     *     nullable=false,
     *     length=255,
     *     options={
     *         "comment" = "Соль"
     *     }
     * )
     *
     * @var string
     */
    protected $salt;

    /**
     * Пароль
     *
     * @JmsAnnotation\Exclude()
     *
     * @ORM\Column(
     *     name="password",
     *     type="string",
     *     nullable=false,
     *     length=64,
     *     options={
     *         "comment" = "Пароль"
     *     }
     * )
     *
     * @var string
     */
    protected $password;

    /**
     * Хэш смены пароля
     *
     * @JmsAnnotation\Type("string")
     * @JmsAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="reset_password_token",
     *     type="string",
     *     nullable=true,
     *     length=255,
     *     options={
     *         "comment" = "Хэш смены пароля"
     *     }
     * )
     *
     * @var string|null
     */
    protected $resetPasswordToken;

    /**
     * Дата запроса на смену пароля
     *
     * @JmsAnnotation\Type("DateTime<'Y-m-d H:i:s'>")
     * @JmsAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="reset_password_requested_at",
     *     type="datetime",
     *     nullable=true,
     *     options={
     *         "comment" = "Дата запроса на смену пароля"
     *     }
     * )
     *
     * @var \DateTime|null
     */
    protected $resetPasswordRequestedAt;

    /**
     * Код подтверждения регистрации
     *
     * @JmsAnnotation\Type("string")
     * @JmsAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="confirmation_token",
     *     type="string",
     *     nullable=true,
     *     length=255,
     *     options={
     *         "comment" = "Код подтверждения регистрации"
     *     }
     * )
     *
     * @var string
     */
    protected $confirmationToken;

    /**
     * Дата последней авторизации"
     *
     * @JmsAnnotation\Type("DateTime<'Y-m-d H:i:s'>")
     * @JmsAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="last_login_at",
     *     type="datetime",
     *     nullable=true,
     *     options={
     *         "comment" = "Дата последней авторизации"
     *     }
     * )
     *
     * @var \DateTime
     */
    protected $lastLoginAt;

    /**
     * Роли
     *
     * @JmsAnnotation\Exclude()
     *
     * @ORM\ManyToMany(
     *     targetEntity="UserRole",
     *     cascade={
     *         "remove"
     *     }
     * )
     * @ORM\JoinTable(name="users_roles_relation",
     *      joinColumns={
     *          @ORM\JoinColumn(
     *              name="user_id",
     *              referencedColumnName="id",
     *              onDelete="CASCADE"
     *          )
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(
     *              name="role_id",
     *              referencedColumnName="id",
     *              onDelete="CASCADE"
     *          )
     *      }
     * )
     *
     * @var ArrayCollection
     */
    protected $roles;

    /**
     * Открытый пароль
     *
     * @JmsAnnotation\Exclude()
     *
     * @Assert\NotBlank()
     * @Assert\Length(max = 4096)
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * Созданные пользователем торренты
     * @JmsAnnotation\Exclude()
     *
     * @ORM\OneToMany(
     *     targetEntity = "AppBundle\Entity\Torrent",
     *     mappedBy     = "createdByUser",
     *     cascade      = {
     *         "persist",
     *         "remove"
     *     }
     * )
     *
     * @var ArrayCollection
     */
    protected $createdTorrents;

    public function __construct()
    {
        $this->salt = $this->generateRandomSalt();
        $this->roles = new ArrayCollection;
    }

    /** @return string */
    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * @inheritdoc
     *
     * @param string $serialized
     *
     * @return void
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $data = array_merge($data, array_fill(0, 2, null));

        list($this->password, $this->salt, $this->userName, $this->id) = $data;
    }

    /**
     * @inheritdoc
     *
     * @return boolean
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @inheritdoc
     *
     * @return boolean
     */
    public function isAccountNonLocked()
    {
        return UserStatusEnumType::STATUS_BANNED !== $this->getStatusId();
    }

    /**
     * @inheritdoc
     *
     * @return boolean
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @inheritdoc
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return UserStatusEnumType::STATUS_DELETED !== $this->getStatusId();
    }

    /**
     * Подтверждён ли пользователь
     *
     * @return boolean
     */
    public function isConfirmed()
    {
        return UserStatusEnumType::STATUS_CONFIRMED === $this->getStatusId();
    }

    /**
     * Подтверждение регистрации
     *
     * @return void
     */
    public function markAsConfirmed()
    {
        $this->setStatusId(UserStatusEnumType::STATUS_CONFIRMED);
        $this->setConfirmationToken('');
    }

    /**
     * Добавление роли
     *
     * @param UserRole $role
     *
     * @return $this
     */
    public function addRole(UserRole $role)
    {
        if(false === $this->roles->contains($role))
        {
            $this->roles->add($role);
        }

        return $this;
    }

    /**
     * Геттер списка ролей
     *
     * @return array
     */
    public function getRolesThree()
    {
        $rolesThree = [];

        foreach($this->roles as $role)
        {
            $this->extractRoles($role, $rolesThree);
        }

        return $rolesThree;
    }


    /**
     * Извлекает роли
     *
     * @param UserRole $role
     * @param array $roles
     *
     * @return $this
     */
    protected function extractRoles(UserRole $role, array &$roles)
    {
        $roles[$role->getName()] = $role->getName();

        if($role->hasParent())
        {
            return $this->extractRoles($role->getParent(), $roles);
        }

        return $this;
    }

    /**
     * Сеттер никнейма
     *
     * @param string $userName
     *
     * @return $this
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }


    /**
     * Сеттер соли
     *
     * @param string $salt
     *
     * @return $this
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Сеттер пароля
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Сеттер токена подтверждения
     *
     * @param string $confirmationToken
     *
     * @return $this
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = '' !== (string) $confirmationToken
            ? $confirmationToken
            : null;

        return $this;
    }

    /**
     * Сеттер даты последней авторизации
     *
     * @param \DateTime $lastLoginAt
     *
     * @return $this
     */
    public function setLastLoginAt(\DateTime $lastLoginAt)
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    /**
     * Сеттер ролей
     *
     * @param ArrayCollection $roles
     *
     * @return $this
     */
    public function setRoles(ArrayCollection $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Сеттер открытого пароля
     *
     * @param string $plainPassword
     *
     * @return $this
     */
    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Сеттер хэша смены пароля
     *
     * @param string $resetPasswordToken
     *
     * @return $this
     */
    public function setResetPasswordToken(string $resetPasswordToken)
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    /**
     * Сеттер даты запроса на смену пароля
     *
     * @param \DateTime|null $resetPasswordRequestedAt
     *
     * @return $this
     */
    public function setResetPasswordRequestedAt(\DateTime $resetPasswordRequestedAt = null)
    {
        $this->resetPasswordRequestedAt = '' !== $resetPasswordRequestedAt
            ? $resetPasswordRequestedAt
            : null;

        return $this;
    }

    /**
     * Сеттер истории изменения баланса
     *
     * @param ArrayCollection $balanceHistory
     *
     * @return $this
     */
    public function setBalanceHistory(ArrayCollection $balanceHistory)
    {
        $this->balanceHistory = $balanceHistory;

        return $this;
    }

    /**
     * Геттер Хэша для смены пароля
     *
     * @return string
     */
    public function getResetPasswordToken(): string
    {
        return (string) $this->resetPasswordToken;
    }

    /**
     * Геттер даты запроса на смену пароля
     *
     * @return \DateTime|null
     */
    public function getResetPasswordRequestedAt()
    {
        return $this->resetPasswordRequestedAt;
    }

    /**
     * Геттер открытого пароля
     *
     * @return string
     */
    public function getPlainPassword(): string
    {
        return (string) $this->plainPassword;
    }

    /**
     * @inheritdoc
     *
     * @return []
     */
    public function getRoles()
    {
        return $this->getRolesThree();
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getSalt()
    {
        // The bcrypt algorithm doesn't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
//        return $this->salt;
        return null;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->userName;
    }

    /**
     * @inheritdoc
     *
     * @return void
     */
    public function eraseCredentials()
    {
        $this->plainPassword = '';
    }

    /**
     * Геттер токена подтверждения
     *
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Геттер даты последней авторизации
     *
     * @return \DateTime
     */
    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    /**
     * Get statusId
     *
     * @return integer
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * Set statusId
     *
     * @param integer $statusId
     *
     * @return User
     */
    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * Генерация соли
     *
     * @return string
     */
    public function generateRandomSalt(): string
    {
        return base_convert(sha1(uniqid((string) mt_rand(), true)), 16, 36);
    }

    /**
     * Может ли пользователь восстановить пароль
     *
     * @return boolean
     */
    public function canRestorePassword(): bool
    {
        if(null !== $this->getResetPasswordRequestedAt() && '' !== $this->getResetPasswordToken())
        {
            $expiredAt = clone $this->getResetPasswordRequestedAt();
            $expiredAt->modify(self::RESET_PASSWORD_LIFETIME_MODIFIER);

            return $expiredAt >= $this->getResetPasswordRequestedAt();
        }

        return false;
    }

    /**
     * Роли пользователя
     *
     * @JmsAnnotation\VirtualProperty
     * @JmsAnnotation\SerializedName("roles")
     * @JmsAnnotation\Type("string")
     * @JmsAnnotation\Since("1.0")
     *
     * @return string
     */
    public function simpleRoles(): string
    {
        return implode(', ', $this->getRolesThree());
    }

    /**
     * @return ArrayCollection
     */
    public function getCreatedTorrents(): ArrayCollection
    {
        return $this->createdTorrents;
    }

    /**
     * @param ArrayCollection $createdTorrents
     */
    public function setCreatedTorrents(ArrayCollection $createdTorrents)
    {
        $this->createdTorrents = $createdTorrents;
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

    /**
     * @return null|string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
}
