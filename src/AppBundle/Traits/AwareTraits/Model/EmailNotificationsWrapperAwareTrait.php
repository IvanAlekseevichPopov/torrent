<?php

declare(strict_types = 1);

namespace AppBundle\Traits\AwareTraits\Model;

use AppBundle\Model\EmailNotifications\EmailNotificationsWrapper;

/**
 *
 *
 * @author Попов Иван
 */
trait EmailNotificationsWrapperAwareTrait
{
    /** @var EmailNotificationsWrapper */
    private $emailNotificationsWrapper;

    /**
     * Сеттер враппера над отправкой почты
     *
     * @param EmailNotificationsWrapper $emailNotificationsWrapper
     *
     * @return $this
     */
    protected function setEmailNotificationsWrapper(EmailNotificationsWrapper $emailNotificationsWrapper)
    {
        $this->emailNotificationsWrapper = $emailNotificationsWrapper;

        return $this;
    }

    /**
     * Геттер враппера над отправкой почты
     *
     * @return EmailNotificationsWrapper
     */
    protected function getEmailNotificationsWrapper(): EmailNotificationsWrapper
    {
        return $this->emailNotificationsWrapper;
    }
}
