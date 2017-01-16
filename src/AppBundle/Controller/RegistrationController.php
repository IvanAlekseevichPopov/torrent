<?php
namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use JMS\Serializer\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
            $this->saveUser($user);

            $this->sendConfirmation($user);
            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

//            return $this->redirectToRoute('user_show');
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Обработка подтверждения email
     *
     * @Route("/register/confirmation/{id}/{token}", name="register_confirmation")
     * @Method({"GET"})
     *
     * @param string  $id
     * @param string  $token
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \InvalidArgumentException
     */
    public function confirmationAction($id, $token, Request $request)
    {
        $confirmResult = $this->getConfirmAnswer($id, $token);

        if ($request->isMethod('GET')) {
            //TODO Нужно сделать шаблон ошибки, сейчас просто посреди страницы
            return $this->render('@App/Security/registragion_confirm.html.twig', ['success' => $confirmResult]);
        } else {
            return new JsonResponse(['success' => $confirmResult]);
        }
    }

    /**
     * @deprecated Убрать из контроллера, убрать dump
     * @param User $user
     */
    protected function saveUser(User $user)
    {
        try {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        } catch (Exception $e){
            //TODO убрать dump
            dump($e->getMessage());
        }
    }

    /**
     * Генерация токена подтверждения регистрации
     *
     * @return string
     */
    protected function generateConfirmationToken(): string
    {
        return sha1(random_bytes(16));
    }

    /**
     * Генерация URL подтверждения регистрации
     *
     * @param User   $user
     * @param string $confirmationToken
     *
     * @return string
     */
    protected function generateConfirmationUrl(User $user, string $confirmationToken): string
    {
        $router = $this->get('router');
        return $router->generate(
            'register_confirmation', ['id' => $user->getId(), 'token' => $confirmationToken],
            true
        );
    }

    private function sendConfirmation($user)
    {
        $confirmationToken = $this->generateConfirmationToken();
        $confirmationUrl   = $this->generateConfirmationUrl($user, $confirmationToken);
        $messageContent    = $this->renderConfirmationMailTemplateContent($user, $confirmationUrl, $confirmationToken);

        $user->setConfirmationToken($confirmationToken);

        $this->getDoctrine()->getEntityManager()->flush($user);

        return $this->handleSend($user, $messageContent, $this->confirmationSubject);
    }

    /**
     * Генерация HTML сообщения о подверждении регистрации
     *
     * @param User   $user
     * @param string $confirmationUrl
     *
     * @param string $token`
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function renderConfirmationMailTemplateContent(User $user, string $confirmationUrl, string $token): string
    {
        return $this->render(
            ':registration:emailConfirmation.html.twig',
            [
                'user'            => $user,
                'confirmationUrl' => $confirmationUrl,
                'token'           => $token,
            ]
        );
    }

    private function handleSend($user, $messageContent, $confirmationSubject)
    {
        dump('hanlde send');
    }
}