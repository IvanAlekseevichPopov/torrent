<?php

declare(strict_types = 1);

namespace AppBundle\Model\Users\PasswordRestore;

use AppBundle\Controller\Security\ResettingController;
use AppBundle\Entity\User;
use AppBundle\Formatter\ExceptionMessageFormatter;
use AppBundle\Manager\Users\UserManager;
use AppBundle\Model\EmailNotifications\EmailNotificationsWrapper;
use Symfony\Component\HttpFoundation as SymfonyHttpFoundation;
use AppBundle\Traits\AwareTraits as AppAwareTraits;
use Symfony\Component\Form as SymfonyForm;
use Psr\Log as PsrLog;
use Symfony\Bundle\FrameworkBundle as SymfonyFrameworkBundle;
use Symfony\Component\HttpKernel as SymfonyHttpKernelException;
use AppBundle\Form\Security as AppSecurityForm;

/**
 * Отправка инструкций о восстановлении пароля на почту
 *
 * @author Попов Иван
 */
class RestorePasswordHandler
{
    use AppAwareTraits\Manager\Users\UserManagerAwareTrait;
    use AppAwareTraits\RouterAwareTrait;
    use AppAwareTraits\TwigEngineAwareTrait;
    use AppAwareTraits\Model\EmailNotificationsWrapperAwareTrait;

    use PsrLog\LoggerAwareTrait;

    const RESTORE_MAIL_SUBJECT = 'Запрос на восстановление пароля';

    /**
     * @param UserManager                                       $userManager
     * @param EmailNotificationsWrapper                         $emailNotificationsWrapper
     * @param SymfonyFrameworkBundle\Routing\Router             $router
     * @param SymfonyFrameworkBundle\Templating\EngineInterface $twigEngine
     * @param PsrLog\LoggerInterface                            $logger
     */
    public function __construct(
        UserManager $userManager, EmailNotificationsWrapper $emailNotificationsWrapper,
        SymfonyFrameworkBundle\Routing\Router $router, SymfonyFrameworkBundle\Templating\EngineInterface $twigEngine,
        PsrLog\LoggerInterface $logger
    )
    {
        $this
            ->setUserManager($userManager)
            ->setEmailNotificationsWrapper($emailNotificationsWrapper)
            ->setRouter($router)
            ->setTwigEngine($twigEngine)
            ->setLogger($logger);
    }

    /**
     * Отправка инструкций на смену пароля
     *
     * @param SymfonyForm\Form              $resetPasswordForm
     * @param SymfonyHttpFoundation\Request $request
     *
     * @return boolean
     */
    public function handleSendInstructions(SymfonyForm\Form $resetPasswordForm, SymfonyHttpFoundation\Request $request
    ): bool
    {
        try
        {
            $userName = (string) $resetPasswordForm->getData()['userName'];

            $user = $this
                ->loadUser($userName)
                ->setResetPasswordToken($this->generateHash($userName))
                ->setResetPasswordRequestedAt(new \DateTime('NOW'));

            $this->getUserManager()->flush($user);

            $templateParameters = [
                'user'        => $user,
                'current_ip'  => $request->getClientIp(),
                'restore_url' => $this->generatePasswordRestoreUrl($user)
            ];

            $messageContent = $this->renderTemplate($templateParameters);

            return $this->sendEmail($user, $messageContent);
        }
        catch(\Exception $e)
        {
            $this->handleException($e, $resetPasswordForm);
        }

        return false;
    }

    /**
     * Выполнение сброса пароля
     *
     * @param SymfonyForm\Form              $passwordChangeForm
     * @param SymfonyHttpFoundation\Request $request
     *
     * @return boolean
     */
    public function handleChangePassword(SymfonyForm\Form $passwordChangeForm, SymfonyHttpFoundation\Request $request
    ): bool
    {
        try
        {
            $user = $this->loadUser($request->get('email'));
            $token = (string) $request->get('token');

            if($user->getResetPasswordToken() === $token)
            {
                if(false === $user->canRestorePassword())
                {
                    throw new SymfonyHttpKernelException\Exception\BadRequestHttpException(
                        'Невозможно начать процедуру смена пароля'
                    );
                }

                if(true === $passwordChangeForm->isSubmitted() && true === $passwordChangeForm->isValid())
                {
                    $user
                        ->setPlainPassword($passwordChangeForm->getData()['newPassword'])
                        ->setConfirmationToken('')
                        ->setResetPasswordRequestedAt(null);

                    $this->getUserManager()->handleResetPassword($user);

                    return true;
                }

                return false;
            }

            throw new SymfonyHttpKernelException\Exception\BadRequestHttpException('Некорректный токен');
        }
        catch(\Exception $e)
        {
            $this->handleException($e, $passwordChangeForm);
        }

        return false;
    }

    /**
     * Обработка исключения
     *
     * @param \Exception       $e
     * @param SymfonyForm\Form $form
     *
     * @return void
     */
    protected function handleException(\Exception $e, SymfonyForm\Form $form)
    {
        if($e instanceof SymfonyHttpKernelException\Exception\HttpException)
        {
            $form->addError(new SymfonyForm\FormError($e->getMessage()));
        }
        else
        {
            $this->logger->critical(ExceptionMessageFormatter::exceptionToString($e));

            $form->addError(
                new SymfonyForm\FormError('Ошибка выполнения операции. Пожалуйста, попробуйте позже')
            );
        }
    }

    /**
     * Отправка сообщения пользователю
     *
     * @param User   $user
     * @param string $messageContent
     *
     * @return boolean
     *
     * @throws SymfonyHttpKernelException\Exception\ServiceUnavailableHttpException
     */
    protected function sendEmail(User $user, string $messageContent): bool
    {
        try
        {
            return $this
                ->getEmailNotificationsWrapper()
                ->execute(
                    $user->getUserEmail(), self::RESTORE_MAIL_SUBJECT, $messageContent
                );
        }
        catch(\RuntimeException $e)
        {
            throw new SymfonyHttpKernelException\Exception\ServiceUnavailableHttpException(
                null, 'Ошибка при отправке письма. Пожалуйста, попробуйте позже', $e
            );
        }
    }

    /**
     * Генерация тела сообщения
     *
     * @param User                          $user
     * @param SymfonyHttpFoundation\Request $request
     *
     * @return string
     */
    protected function createMessageContent(User $user, SymfonyHttpFoundation\Request $request): string
    {
        $templateParameters = [
            'user'        => $user,
            'current_ip'  => $request->getClientIp(),
            'restore_url' => $this->generatePasswordRestoreUrl($user)
        ];

        return $this->renderTemplate($templateParameters);
    }

    /**
     * Рендеринг шаблона письма
     *
     * @param array $templateParameters
     *
     * @return string
     */
    protected function renderTemplate(array $templateParameters): string
    {
        return $this
            ->getTwigEngine()
            ->render(ResettingController::PASSWORD_RESTORE_REQUEST_TEMPLATE_ID, $templateParameters);
    }

    /**
     * Генерация URL для смены пароля
     *
     * @param User $user
     *
     * @return string
     */
    protected function generatePasswordRestoreUrl(User $user): string
    {
        return $this
            ->getRouter()
            ->generate(
                ResettingController::ROUTE_USER_PASSWORD_RESTORE_FINALIZE,
                ['email' => $user->getUserEmail(), 'token' => $user->getResetPasswordToken()],
                true
            );
    }

    /**
     * Генерация случайного хэша
     *
     * @param string $userName
     *
     * @return string
     */
    protected function generateHash(string $userName): string
    {
        return sha1(random_bytes(16) . ':' . $userName);
    }

    /**
     * Загрузка пользователя
     *
     * @param string $userName
     *
     * @return User
     *
     * @throws SymfonyHttpKernelException\Exception\BadRequestHttpException
     */
    protected function loadUser(string $userName): User
    {
        $user = $this->getUserManager()->findOneByEmail($userName);

        if(null !== $user)
        {
            return $user;
        }

        throw new SymfonyHttpKernelException\Exception\BadRequestHttpException('Пользователь не найден');
    }
}
