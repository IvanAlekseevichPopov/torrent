<?php

declare(strict_types = 1);

namespace AppBundle\Services;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

        if($user->getConfirmationToken() !== $token){
            return false;
        }

        $user->markAsConfirmed();
        $this->manager->persist($user);
        $this->manager->flush();

        return true;
    }

    /**
     * Записываем в пользователя новый токен
     *
     * @param User $user
     * @return bool
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