<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {

    }

//    /**
//     * @Route("/restore", name="password_restore")
//     */
//    public function passwordRestoreAction(Request $request)
//    {
//        $result = null;
//        if ($request->isMethod('POST')) {
//            //TODO возможно доп проверка email
//            $user = $this->getUserByEmail($request->request->get('_email'));
//            if (null === $user) {
//                //TODO translate
//                return $this->render('security/restore.html.twig', [
//                    'result' => 'Пользователь с указанным email не существует',
//                ]);
//            }
//
//            $this->get('app.user_helper')->handleResetPassword($user);
//            //TODO translate
//            return $this->redirectToRoute('login');
//        }
//
//        return $this->render('security/restore.html.twig');
//    }
//
//    public function getUserByEmail(string $email)
//    {
//        //TODO убрать в user manager
//        return
//            $this
//                ->getDoctrine()
//                ->getRepository(User::class)
//                ->findOneBy(['email' => $email]);
//    }
}