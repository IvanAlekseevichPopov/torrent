<?php

declare(strict_types = 1);

namespace AppBundle\Model\Users\Registration;

use AppBundle\Controller\RegistrationController;
use AppBundle\Entity\User;
use AppBundle\Manager\Users\UserManager;
use AppBundle\Model\EmailNotifications\EmailNotificationsWrapper;
use AppBundle\Traits\AwareTraits as AppAwareTraits;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bundle\FrameworkBundle\{
    Routing\Router, Templating\EngineInterface
};
use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException, NotFoundHttpException
};
use Symfony\Component\Security\Core\Authentication\Token\{
    Storage\TokenStorage,
    UsernamePasswordToken
};
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Обработка токена подтверждения регистрации
 *
 * @author Попов Иван
 */
class RegistrationConfirmationHandler
{
    use AppAwareTraits\Manager\Users\UserManagerAwareTrait;
    use AppAwareTraits\Model\EmailNotificationsWrapperAwareTrait;
    use AppAwareTraits\TwigEngineAwareTrait;
    use AppAwareTraits\RouterAwareTrait;

    /** @var string */
    protected $confirmationSubject;

    /**
     * @param UserManager               $userManager
     * @param EmailNotificationsWrapper $emailNotificationsWrapper
     * @param Router                    $router
     * @param EngineInterface           $twigEngine
     * @param string                    $confirmationSubject
     */
    public function __construct(
        UserManager $userManager, EmailNotificationsWrapper $emailNotificationsWrapper, Router $router,
        EngineInterface $twigEngine,
        string $confirmationSubject
    )
    {
        $this->confirmationSubject = $confirmationSubject;

        $this
            ->setEmailNotificationsWrapper($emailNotificationsWrapper)
            ->setTwigEngine($twigEngine)
            ->setRouter($router)
            ->setUserManager($userManager);
    }

    /**
     * Отправка сообщения подтверждения регистрации
     *
     * @param User $user
     *
     * @return boolean
     * @throws \RuntimeException
     */
    public function handleRegistration(User $user)
    {
        $confirmationToken = $this->generateConfirmationToken();
        $confirmationUrl   = $this->generateConfirmationUrl($user, $confirmationToken);
        $messageContent    = $this->renderConfirmationMailTemplateContent($user, $confirmationUrl, $confirmationToken);

        $user->setConfirmationToken($confirmationToken);

        $this->getUserManager()->flush($user);

        return $this->handleSend($user, $messageContent, $this->confirmationSubject);
    }

    /**
     * Генерация токена подтверждения регистрации
     *
     * @return string
     */
    protected function generateConfirmationToken(): string
    {
        return sha1(random_bytes(16));
    }

    /**
     * Генерация URL подтверждения регистрации
     *
     * @param User   $user
     * @param string $confirmationToken
     *
     * @return string
     */
    protected function generateConfirmationUrl(User $user, string $confirmationToken): string
    {
        return $this->router->generate(
            RegistrationController::ROUTE_USER_CONFIRMATION, ['id' => $user->getId(), 'token' => $confirmationToken],
            true
        );
    }

    /**
     * Генерация HTML сообщения о подверждении регистрации
     *
     * @param User   $user
     * @param string $confirmationUrl
     *
     * @param string $token
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function renderConfirmationMailTemplateContent(User $user, string $confirmationUrl, string $token): string
    {
        return $this->twigEngine->render(
            RegistrationController::CONFIRMATION_MESSAGE_TEMPLATE_ID,
            [
                'user'            => $user,
                'confirmationUrl' => $confirmationUrl,
                'token'           => $token,
            ]
        );
    }

    /**
     * Выполнение отправки сообщения
     *
     * @param User   $user
     * @param string $messageContent
     * @param string $messageSubject
     *
     * @return boolean
     */
    protected function handleSend(User $user, string $messageContent, string $messageSubject)
    {
        return $this->emailNotificationsWrapper->execute(
            $user->getEmail(), $messageSubject, $messageContent
        );
    }

    /**
     * Обработка подверждения регистрации
     *
     * @param string       $userId
     * @param string       $token
     * @param TokenStorage $tokenStorage
     *
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function handleConfirmation(string $userId, string $token, TokenStorage $tokenStorage)
    {
        $user = $this->loadUserById($userId);

        if (false === $user->isAccountNonLocked() || false === $user->isEnabled()) {
            throw new BadRequestHttpException('Пользователь неактивен');
        }

        if (false === $user->isConfirmed()) {
            if ('' !== (string)$token && $user->getConfirmationToken() === $token) {
                $this->onConfirmationSuccess($user);
            } else {
                throw new BadRequestHttpException('Некорректный токен');
            }
        }

        //Авторизация
        $token = new UsernamePasswordToken(
            $user, null, 'app.security.user_login_check', $user->getRoles()
        );

        $tokenStorage->setToken($token);

        return $this;
    }

    /**
     * Поиск пользователя
     *
     * @param string $userId
     *
     * @return User
     *
     * @throws NotFoundHttpException
     */
    protected function loadUserById(string $userId): User
    {
        $user = $this->getUserManager()->findOneById($userId);

        if (null !== $user) {
            return $user;
        }

        throw new NotFoundHttpException(sprintf('Пользователь `%s` не найден', $userId));
    }

    /**
     * Отмечаем у пользователя успешное подтверждение
     *
     * @param User $user
     *
     * @return void
     */
    protected function onConfirmationSuccess(User $user)
    {
        $user->markAsConfirmed();

        $this->getUserManager()->flush($user);
    }
}
