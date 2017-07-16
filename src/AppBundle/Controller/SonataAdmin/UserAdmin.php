<?php

declare(strict_types = 1);

namespace AppBundle\Controller\SonataAdmin;

use AppBundle\DBAL\Types\Enum\Users\UserStatusEnumType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Admin panel for Users list
 *
 * @author Popov Ivan <ivan.alekseevich.popov@gmail.com>
 */
class UserAdmin extends AbstractAdmin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('userName', 'text')
            ->add('userEmail', 'text')
            ->add('status', 'choice', ['choices' => UserStatusEnumType::getChoices()]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('userName')
            ->add('userEmail');
        //TODO рабочий вариант для symfony3! из symfony2 не работает (
//            ->add('createdAt','doctrine_orm_datetime_range', [],'sonata_type_datetime_range_picker',
//                array('field_options_start' => array('format' => 'yyyy-MM-dd HH:mm:ss'),
//                    'field_options_end' => array('format' => 'yyyy-MM-dd HH:mm:ss'))
//            );
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('userName')
            ->add('userEmail')
            ->add('status', 'choice', ['choices' => UserStatusEnumType::getReadableValues()])
            ->add('createdAt', 'datetime', ['format' => 'H:i:s d.m.y'])
            ->add('updatedAt','datetime', ['format' => 'H:i:s d.m.y']);

    }

}
