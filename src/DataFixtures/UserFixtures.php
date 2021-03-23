<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public const ADMIN_USER_REFERENCE = 'admin-user';
    public const STANDARD_USER_REFERENCE = 'standard-user';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();

        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_MODERATOR']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'admin'
        ));

        $manager->persist($admin);

        $user = new User();

        $user->setUsername('user');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'user'
        ));

        $manager->persist($user);

        $manager->flush();

        $this->addReference(self::ADMIN_USER_REFERENCE, $admin);
        $this->addReference(self::STANDARD_USER_REFERENCE, $user);
    }
}
