<?php

declare(strict_types = 1);

namespace AppBundle\DataFixtures\ORM\Users;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Фикстуры пользователей
 * @author Попов Иван
 */
class UsersDataFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @inheritdoc
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = (new User)
            ->setUserEmail('admin@email.com')
            ->setUserName('admin')
            ->setPassword('$2y$13$J1Cjit4GqI2kmMf4XzswFuR72r5FyIg0.WI6rgxjPpz0uATqKXno.')
            ->setSalt('nl2i0b1u1i8g8wc4gcsgs80gg404w80')
            ->addRole($this->getReference(UserRole::class.UserRole::ROLE_ADMIN));

        $manager->persist($user);
        $manager->flush();
    }

    /**
     * Порядок выполнения фикстуры
     *
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}