<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UsersFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordEncoder, private SluggerInterface $slugger)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@demo.com');
        $admin->setLastname('Zariat');
        $admin->setFirstname('Anis');
        $admin->setAddress('16 Rue Taeib El Mhiri Beni Khiar');
        $admin->setCity('Beni Khiar');
        $admin->setZipcode('8060');
        $admin->setRoles(['ADMIN_ROLE']);
        $admin->setPassword($this->passwordEncoder->hashPassword($admin, 'anis123'));
        $manager->persist($admin);

        $faker = Faker\Factory::create();
        for ($usr=0; $usr <10 ; $usr++) {
            $user = new User();
            $user->setLastname($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setEmail(strtolower($user->getLastname()).'-'.strtolower($user->getFirstname()).substr($faker->email, strrpos($faker->email, '@')));
            $user->setAddress($faker->address);
            $user->setCity($faker->city);
            $user->setZipcode(str_replace(' ', '', $faker->postcode));
            $user->setRoles([]);
            $user->setPassword($this->passwordEncoder->hashPassword($user, strtolower($user->getFirstname()).'123'));
            $manager->persist($user);

        }

        $manager->flush();
    }
}
