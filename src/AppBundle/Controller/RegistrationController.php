<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
// 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

// 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

// 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setConfirmationToken($this->getUniqueToken());

// 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

// 5) send confirm url to email
            $this->sendRegisterConfirm($user);

// ... do any other work - like sending them an email, etc
// maybe set a "flash" success message for the user

            return $this->redirectToRoute('user_registration');
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Подтверждение регстрации через email
     *
     * @Route("/register/confirm/{userId}/{token}", name="user_registration_confirmation")
     * @param Request $request
     * @param $userId
     * @param $token
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerConfirmationAction(Request $request, $userId, $token)
    {
//        TODO
//        Сверяем пользователя и токен через try
//        Совпало - обновляем пользователя
//        Не совпало - отфутболиваем

//        TODO Если пользователь не подтвердил email за неделю - удаляем его из базы
        dump($request);
        dump($userId);
        dump($token);
        return $this->render(
            'registration/register_email_confirm.html.twig'
        );
    }

    protected function sendRegisterConfirm(User $user)
    {
        //        TODO move from controller
        $email = $user->getUserEmail();
        dump($email);

        $url =
            $this->get('request_stack')->getCurrentRequest()->getSchemeAndHttpHost()."/".
            $this->get('router')->generate('user_registration_confirmation')."/".
            $user->getId()."/".
            $user->getConfirmationToken();
        dump($url);

        $message = \Swift_Message::newInstance()
            ->setSubject('Email confirmation')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'email/register_confirm.html.twig',
                    [
                        'confirmationUrl' => $url
                    ]
                )
            );

        $this->get('mailer')->send($message);
    }

    /**
     * Генератор уникального токена
     *
     * @param int $count
     * @return string
     */
    protected function getUniqueToken(int $count = 16): string
    {
//        TODO move from controller
        return bin2hex(openssl_random_pseudo_bytes($count));
    }

}