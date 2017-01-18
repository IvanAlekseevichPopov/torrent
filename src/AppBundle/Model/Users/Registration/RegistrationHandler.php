<?php

declare(strict_types = 1);

namespace AppBundle\Model\Users\Registration;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use AppBundle\Manager\Users\UserManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Traits\AwareTraits\Manager\Users\
{
    UserManagerAwareTrait
};

class RegistrationHandler
{
    use UserManagerAwareTrait;

    /** @var RegistrationConfirmationHandler */
    protected $registrationConfirmationHandler;

    /**
     * @param UserManager                     $userManager
     * @param RegistrationConfirmationHandler $registrationConfirmationHandler
     */
    public function __construct(
        UserManager $userManager,
        RegistrationConfirmationHandler $registrationConfirmationHandler
    )
    {
        $this->registrationConfirmationHandler = $registrationConfirmationHandler;

        $this->setUserManager($userManager);
    }

    /**
     * Выполнение регистрации пользователя
     *
     * @param Form    $registrationForm
     * @param Request $request
     *
     * @return string
     *
     * @throws \Exception
     */
    public function handle(Form $registrationForm, Request $request): string
    {
        $userInstance = new User;

        $registrationForm->setData($userInstance)->handleRequest($request);

        if(true === $registrationForm->isSubmitted() && true === $registrationForm->isValid())
        {
            $this->getUserManager()->beginTransaction();

            try
            {
                $this->getUserManager()->handleRegisterUser($userInstance);
                $this->getUserManager()->addRole($userInstance, UserRole::ROLE_USER);

                $this->getUserManager()->commit();
            }
            catch(\Exception $e)
            {
                $this->getUserManager()->rollback();

                throw $e;
            }

            $this->registrationConfirmationHandler->handleRegistration($userInstance);

            return (string) $userInstance->getId();
        }

        return '';
    }
}
