<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserRegisterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{

    /**
     * Регистрация. Первый шаг - отправка проверки на email
     *
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
// 1) build the form
        $user = new User();
        $form = $this->createForm(UserRegisterType::class, $user);

// 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

// 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

// 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

// 5) send confirm url to email
            $this->get('app.user_helper')->handleConfirmRegistration($user);

            return $this->redirectToRoute('user_registration');
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Регистрация. Второй шаг - подтверждение регстрации через email
     *
     * @Route("/register/confirm/{userId}/{token}", name="user_registration_confirmation")
     * @Method("GET")
     *
     * @param $userId
     * @param $token
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerConfirmationAction($userId, $token)
    {
        $result = $this->get('app.user_helper')->checkRegisterConfirmation($userId, $token, $this->get('security.token_storage'));

        if ($result) {
            //TODO multilang
            $message = 'success register confirmation';
        } else {
            //TODO multilang
            $message = 'Error confirmation. Maybe you are already confirm email?';
        }

//        TODO Если пользователь не подтвердил email за неделю - удаляем его из базы

        return $this->render('registration/register_email_confirm.html.twig', [
            'message' => $message
        ]);
    }
}