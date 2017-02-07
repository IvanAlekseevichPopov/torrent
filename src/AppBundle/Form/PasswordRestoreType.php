<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordRestoreType extends AbstractType
{
    /**
     * @inheritdoc
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('actionType', HiddenType::class, ['data' => self::getFormActionType()])
            ->add('process', SubmitType::class, ['label' => 'Продолжить']);
    }

    /**
     * Геттер внутреннего ID действия формы
     *
     * @return string
     */
    public static function getFormActionType(): string
    {
        return 'request';
    }
}