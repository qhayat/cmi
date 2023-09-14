<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setFirstName('John')
            ->setLastName('DOE')
            ->setEmail('j.doe@example.local')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('P@ssword1');

        $user2 = (new User())
            ->setFirstName('Jean')
            ->setLastName('YVE')
            ->setEmail('j.yve@example.local')
            ->setRoles(['ROLE_USER'])
            ->setPassword('P@ssword2');

        $manager->persist($user);
        $manager->persist($user2);

        $manager->flush();
    }
}
