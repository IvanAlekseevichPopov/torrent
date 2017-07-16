<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    /**
     * Логин
     * Не удалять
     *
     * @Method({"GET", "POST"})
     * @Route(
     *     "/login",
     *     name="login",
     *     options={
     *          "expose":true
     *     }
     * )
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
     * Логаут
     * Не удалять
     *
     * @Method("GET")
     * @Route(
     *     "/logout",
     *     name="logout",
     *     options={
     *          "expose":true
     *     }
     * )
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
}