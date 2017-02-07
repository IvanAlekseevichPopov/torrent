<?php

declare(strict_types = 1);

namespace AppBundle\Services;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class UserHelper
{
    protected $container;
    protected $manager;

    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->container = $container;
        $this->manager = $em;
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
        $userRepo = $this->container->get('doctrine')->getRepository(User::class);
        $user = $userRepo->find($userId);

        if (!($user instanceof User)) {
            return false;
        }

        if ($user->getConfirmationToken() !== $token) {
            return false;
        }

        $user->markAsConfirmed();
        $this->addRole($user, UserRole::ROLE_USER);

        $this->manager->persist($user);
        $this->manager->flush();

        return true;
    }

    /**
     * Задаем пользователю роль по названию
     *
     * @param User $user
     * @param string $roleName
     */
    public function addRole(User $user, string $roleName)
    {
        //TODO переместить метод в менеджер пользователей!!!!!!!!!!!!!!!!!!
        //И упростить получение роли. Слишком длинная цепочка
        $userRole = $this->manager->getRepository(UserRole::class)->findBy(['name' => $roleName]);
        if (null === $userRole) {
            throw new \LogicException(
                sprintf('Указана некорректная роль `%s`. См `users_roles`', $roleName)
            );
        }

        $user->addRole($userRole);
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
        $this->manager->persist($user);
        $this->manager->flush();
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