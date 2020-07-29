<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder=$encoder;
    }
    public function load(ObjectManager $manager)
    {
        
    $faker = Factory::create('fr_FR');
        $profils=['ADMIN','FORMATEUR','CM','APPRENANT'];
        for ($i=0; $i <=3; $i++) { 
            
            $profil = new Profil();
            $profil->setLibelle($profils[$i]);
            $manager->persist($profil);
            $manager->flush();
            for ($j=0; $j < 3 ; $j++) { 
            $user = new User();
            $user->setPrenom($faker->firstName)
                 ->setNom($faker->lastName)
                 ->setEmail($faker->safeEmail)
                 ->setAvatar($faker->imageUrl(640,480,'cats'))
                 ->setStatut("on")
                 ->setProfil($profil)
                 ->setUsername(strtolower($profils[$i].($j+1)))
                 ->setPassword($this->encoder->encodePassword($user, "password".($j+1)));
                 
            $manager->persist($user);
        }
        }
         
        $manager->flush();
    }
}
