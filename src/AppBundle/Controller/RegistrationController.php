<?php
namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Model\Users\Registration\RegistrationConfirmationHandler;
use AppBundle\Model\Users\Registration\RegistrationHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RegistrationController extends Controller
{
    //TODO выпилить не тру вызывать такую фигню
    const ROUTE_USER_CONFIRMATION = 'regiser_email_confirmation';
    const CONFIRMATION_MESSAGE_TEMPLATE_ID = 'registration/emailConfirmation.html.twig';

    /**
     * @Route("/register", name="user_registration")
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $registrationForm = $this->createForm(UserType::class);

        if ($request->isMethod('GET')) {
            return $this->render(
                'registration/register.html.twig', array('form' => $registrationForm->createView())
            );
        }

        $userId = $this
            ->getRegistrationHandler()
            ->handle($registrationForm, $request);

        if ('' !== $userId) {
            /** Успех */
            return new JsonResponse([
                'status' => 'success',
                'user_id' => $userId,
            ]);
        }

        /** Ошибка */
        return new JsonResponse($this->getFormErrors($registrationForm));
    }


    /**
     * Обработка подтверждения email
     *
     * @Method({"GET", "POST"})
     * @Route("/register/confirmation/{id}/{token}", name="regiser_email_confirmation")
     *
     * @param string $id
     * @param string $token
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \InvalidArgumentException
     */
    public function confirmationAction($id, $token, Request $request)
    {
        $confirmResult = $this->getConfirmAnswer($id, $token);

        if ($request->isMethod('GET')) {
            return $this->render('registration/registragion_confirm.html.twig', ['success' => $confirmResult]);
        } else {
            return new JsonResponse(['success' => $confirmResult]);
        }
    }

    /**
     * Преобразование ошибок формы в человекочитаемые
     *
     * @param Form $form
     *
     * @return array
     */
    protected function getFormErrors(Form $form)
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $message = $this->container->get('translator')->trans($error->getMessage(), []);
            $errors[] = $message;
        }

        return $errors;
    }

    /**
     * Проверка Url подверждения
     *
     * @param string $userId
     * @param string $token
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected function getConfirmAnswer(string $userId, string $token)
    {
        try {
            $this
                ->getRegistrationConfirmHandler()
                ->handleConfirmation($userId, $token, $this->get('security.token_storage'));

            return true;
        } catch (HttpException $e) {

            return false;
        }
    }

    /** @return RegistrationConfirmationHandler */
    protected function getRegistrationConfirmHandler()
    {
        return $this->get('app.model.users.registration.registration_confirmation_handler');
    }

    /** @return RegistrationHandler */
    protected function getRegistrationHandler()
    {
        return $this->get('app.model.users.registration.registration_handler');
    }
}