<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@mail.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'azerty'
        );
        $this->addReference('user', $user);
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $admin = new User();
        $admin->setEmail('admin@mail.com');
        $admin->setFirstName('Bilbo');
        $admin->setLastName('Baggins');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'raclette'
        );
        $this->addReference('admin', $admin);

        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        $manager->flush();
    }
}
