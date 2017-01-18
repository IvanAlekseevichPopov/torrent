<?php

declare(strict_types = 1);

namespace AppBundle\Traits\AwareTraits\Manager\Users;

use AppBundle\Manager\Users\UserManager;

/**
 *
 *
 * @author Попов Иван
 */
trait UserManagerAwareTrait
{
    /** @var UserManager */
    private $userManager;

    /**
     * Сеттер менеджера пользователей
     *
     * @param UserManager $userManager
     *
     * @return $this
     */
    protected function setUserManager(UserManager $userManager)
    {
        $this->userManager = $userManager;

        return $this;
    }

    /**
     * Геттер менеджера пользователей
     *
     * @return UserManager
     */
    protected function getUserManager(): UserManager
    {
        return $this->userManager;
    }
}
