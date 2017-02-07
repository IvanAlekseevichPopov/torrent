<?php

namespace AppBundle\Controller;

use AppBundle\Form\PasswordRestoreType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends Controller
{
    /**
     * Запрос на смену пароля
     *
     * @Method({"GET", "POST"})
     * @Route("/restore", name="password_restore")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function resetRequestAction(Request $request)
    {
        $requestResetPasswordForm = $this
            ->createForm(PasswordRestoreType::class)
            ->handleRequest($request);

        if (true === $requestResetPasswordForm->isSubmitted() && true === $requestResetPasswordForm->isValid()) {
            $result = $this
                ->getUserHelper()
                ->handleResetPassword($requestResetPasswordForm, $request);

            if (true === $result) {
                return new RedirectResponse(
                    $this->generateUrl('restore_password_email_send')
                );
            }
        }

        return $this->render(
            'security/restore.html.twig', ['form' => $requestResetPasswordForm->createView()]
        );
    }

    /**
     * Рендеринг сообщения об успешной отправке сообщения
     *
     * @Method({"GET"})
     * @Route("/restore/mail", name="restore_password_email_send")
     *
     * @return Response
     */
    public function resetRequestSuccessMessageAction()
    {
        return $this->render('security/restore.html.twig', [
            'mailSent' => true
        ]);
    }

    protected function getUserHelper()
    {
        return $this->get('app.user_helper');
    }
}