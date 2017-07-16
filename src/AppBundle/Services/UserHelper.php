<?php

declare(strict_types = 1);

namespace AppBundle\Services;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use AppBundle\Manager\UserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class UserHelper
{
    protected $container;
    protected $userManager;

    public function __construct(ContainerInterface $container, UserManager $userManager)
    {
        $this->container = $container;
        $this->userManager = $userManager;
    }

    /**
     * Отправка сообщения пользователю для подвержедия регистрации
     *
     * @param User $user
     */
    public function handleConfirmRegistration(User $user)
    {
        $this->setConfirmToken($user);

        $url = $this->generateConfirmUrl($user);

        $message = \Swift_Message::newInstance()
            ->setSubject('Email confirmation')
            ->setTo($user->getUserEmail())
            ->setBody(
                $this->container->get('twig')->render(
                    'email/register_confirm.html.twig',
                    [
                        'confirmationUrl' => $url
                    ]
                )
            );

        $this->container->get('mailer')->send($message);
    }


    /**
     * Отправка сообщения пользователю для подвержедия регистрации
     *
     * @param Form $resetForm
     * @param Request $request
     * @return bool
     */
    public function handleResetPassword(Form $resetForm, Request $request): bool
    {
        //Получаем пользователя
        $this->container->get('app.manager.user_manager')->findUserByEmail($resetForm->getData()['email']);
        //Проверяем на непустоту
        //Генерируем токен
        //Записываем в пользователя
        //Генерируем url
        //Отправляем мыло
        return true;
    }
//    /**
//     * Отправка email с url для сброса пароля
//     *
//     * @param User $user
//     */
//    public function handleResetPassword(User $user)
//    {
//        $this->setConfirmToken($user);
//
//        $url = $this->generateConfirmUrl($user);
//
//        $message = \Swift_Message::newInstance()
//            ->setSubject('Email confirmation')
//            ->setTo($user->getUserEmail())
//            ->setBody(
//                $this->container->get('twig')->render(
//                    'email/register_confirm.html.twig',
//                    [
//                        'confirmationUrl' => $url
//                    ]
//                )
//            );
//
//        $this->container->get('mailer')->send($message);
//    }

    /**
     * Проверка url подтверждения регстрации
     *
     * @param string $userId
     * @param string $token
     * @return bool
     */
    public function checkRegisterConfirmation(string $userId, string $token): bool
    {
        //TODO получать репозиторий проще, чем сейчас
        $userRepo = $this->userManager->getRepository();
        $user = $userRepo->find($userId);

        if (!($user instanceof User)) {
            return false;
        }

        if ($user->getConfirmationToken() !== $token) {
            return false;
        }

        $user->markAsConfirmed();
        $this->userManager->addRole($user, UserRole::ROLE_USER);

        $this->userManager->persist($user);
        $this->userManager->flush();

        return true;
    }

    /**
     * Записываем в пользователя новый токен
     *
     * @param User $user
     */
    protected function setConfirmToken(User $user)
    {
        //TODO подумать над этим
//        try {
        $user->setConfirmationToken($this->container->get('app.unique_token_generator')->getUniqueToken());
        $this->userManager->persist($user);
        $this->userManager->flush();
//        } catch (\Exception $e){
//            $this->container->get('logger')->addError($e->getMessage());
//            return false;
//        }
//
//        return true;
    }

    /**
     * Возвращает сгенерированный url
     *
     * @param User $user
     * @return string
     */
    protected function generateConfirmUrl(User $user): string
    {
        return
            $this->container->get('request_stack')->getCurrentRequest()->getSchemeAndHttpHost() .
            $this->container->get('router')->generate('user_registration_confirmation', [
                'userId' => $user->getId(),
                'token' => $user->getConfirmationToken()
            ]);
    }
}