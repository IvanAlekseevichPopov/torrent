<?php

declare(strict_types = 1);

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use AppBundle\Repository\UserRepository;

class UserManager extends AppManagerAbstract
{
    /**
     * @return UserRepository
     */
    public function getRepository(): UserRepository
    {
        return $this->getEntityManager()->getRepository(User::class);
    }

    /**
     * Возвращает роль по наименованию
     *
     * @param string $roleName
     * @return UserRole|object|null
     */
    public function findRoleByName(string $roleName)
    {
        return $this->getEntityManager()->getRepository(UserRole::class)->findOneBy(['name' => $roleName]);
    }

    /**
     * Задаем пользователю роль по названию
     *
     * @param User $user
     * @param string $roleName
     */
    public function addRole(User $user, string $roleName)
    {
        $userRole = $this->findRoleByName($roleName);

        if (null === $userRole) {
            throw new \LogicException(
                sprintf('Указана некорректная роль `%s`. См `users_roles`', $roleName)
            );
        }

        $user->addRole($userRole);
    }

    /**
     * Возвращает пользователя по email
     *
     * @param string $email
     * @return null|object
     */
    public function getUserByEmail(string $email)
    {
        return
            $this
                ->getRepository()
                ->findOneBy(['email' => $email]);
    }

}
