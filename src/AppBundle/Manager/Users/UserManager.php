<?php

declare(strict_types = 1);

namespace AppBundle\Manager\Users;

use AppBundle\DBAL\Types\Enum\Users\UserStatusEnumType;
use AppBundle\Entity\UserRole;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use AppBundle\Manager\AppManagerAbstract;
use AppBundle\Entity\User;

/**
 * Менеджер юзеров
 *
 * @author Попов Иван
 */
class UserManager extends AppManagerAbstract
{
    /** @var EncoderFactoryInterface */
    protected $passwordEncoder;

    /**
     * @param EncoderFactoryInterface $passwordEncoder
     * @param EntityManager           $entityManager
     * @param ContainerInterface      $container
     */
    public function __construct(
        EncoderFactoryInterface $passwordEncoder, EntityManager $entityManager,
        ContainerInterface $container
    )
    {
        $this->passwordEncoder = $passwordEncoder;

        parent::__construct($entityManager, $container);
    }

    /**
     * Создание пользователя
     *
     * @param User $newUserInstance
     *
     * @return User
     */
    public function handleRegisterUser(User $newUserInstance): User
    {
        /** Хэширование открытого пароля */
        if ('' !== $newUserInstance->getPlainPassword()) {
            $encoder = $this->passwordEncoder->getEncoder($newUserInstance);
            $newUserInstance->setPassword(
                $encoder->encodePassword($newUserInstance->getPlainPassword(), $newUserInstance->getSalt())
            );

            $newUserInstance->eraseCredentials();
        }

        $this->persistAndSave($newUserInstance);

        return $newUserInstance;
    }

    /**
     * Обновление пароля пользователя
     *
     * @param User $user
     *
     * @return User
     */
    public function handleResetPassword(User $user): User
    {
        $salt          = $user->generateRandomSalt();
        $plainPassword = $user->getPlainPassword();
        $encoder       = $this->passwordEncoder->getEncoder($user);

        $password = $encoder->encodePassword($plainPassword, $salt);

        $user
            ->setSalt($salt)
            ->setPassword($password)
            ->eraseCredentials();

        $this->flush($user);

        return $user;
    }

    /**
     * Добавление роли
     *
     * @param User   $user
     * @param string $roleTag
     *
     * @return $this
     *
     * @throws \LogicException
     */
    public function addRole(User $user, string $roleTag)
    {
        $role = $this->getRolesRepository()->findOneByName($roleTag);

        if (null !== $role) {
            $user->addRole($role);

            $this->flush($user);

            return $this;
        }

        throw new \LogicException(
            sprintf('Указана некорректная роль `%s`. См `users_roles`', $roleTag)
        );
    }

    /**
     * Поиск пользователя по Email
     *
     * @param string $userEmail
     *
     * @return User|null
     */
    public function findOneByEmail(string $userEmail)
    {
        return $this
            ->getRepository()
            ->findOneBy(['userEmail' => $userEmail]);
    }

    /**
     * Поиск подтверждённого пользователя по Email
     *
     * @param string $userEmail
     *
     * @return User|null
     */
    public function findOneConfirmedByEmail(string $userEmail)
    {
        return $this
            ->getRepository()
            ->findOneBy(['userEmail' => $userEmail, 'statusId' => UserStatusEnumType::STATUS_CONFIRMED]);
    }

    /**
     * Поиск пользователя по Email
     *
     * @param string $id
     *
     * @return User|null
     */
    public function findOneById(string $id)
    {
        return $this
            ->getRepository()
            ->find($id);
    }

    /**
     * Геттер репозитория пользователя
     *
     * @return \AppBundle\Repository\UserRepository
     *
     */
    public function getRepository()
    {
        return $this->getEntityManager()->getRepository(User::class);
    }

    /**
     * Геттер репозитория по работе с ролями пользователей
     *
     * @return \AppBundle\Repository\UserRoleRepository
     */
    public function getRolesRepository()
    {
        return $this->getEntityManager()->getRepository(UserRole::class);
    }

    /**
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     *
     * @return Logger
     */
    private function getLogger()
    {
        return $this->container->get('logger');
    }
}
