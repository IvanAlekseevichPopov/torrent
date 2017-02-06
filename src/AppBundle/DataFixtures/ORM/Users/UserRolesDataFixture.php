<?php

declare(strict_types = 1);

namespace AppBundle\DataFixtures\ORM\Users;

use AppBundle\Entity\UserRole;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Фикстуры ролей
 * @author Попов Иван
 */
class UserRolesDataFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @inheritdoc
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userRole = (new UserRole)
            ->setName(UserRole::ROLE_USER)
            ->setDescription('Пользователь');

        $manager->persist($userRole);
        $manager->flush();
        $this->setReference(UserRole::class.UserRole::ROLE_USER, $userRole);


        $moderatorRole = (new UserRole)
            ->setName(UserRole::ROLE_MODERATOR)
            ->setDescription('Модератор')
            ->setParent($userRole);

        $manager->persist($moderatorRole);
        $manager->flush();
        $this->setReference(UserRole::class.UserRole::ROLE_MODERATOR, $moderatorRole);


        $adminRole = (new UserRole)
            ->setName(UserRole::ROLE_ADMIN)
            ->setDescription('Администратор')
            ->setParent($moderatorRole);

        $manager->persist($adminRole);
        $manager->flush();
        $this->addReference(UserRole::class.UserRole::ROLE_ADMIN, $adminRole);
    }

    /**
     * Порядок выполнения фикстуры
     *
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }
}