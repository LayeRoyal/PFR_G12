<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Profil;
use App\Entity\User;
use App\Entity\Cm;
use App\Entity\Apprenant;
use App\Entity\Formateur;
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
        for ($i=0; $i <count($profils); $i++) { 
           //Creation des profils 
            $profil = new Profil();
            $profil->setLibelle($profils[$i]);
            $manager->persist($profil);
            $manager->flush();
            //creation des utilisateurs
            for ($j=0; $j < 3 ; $j++) { 
                if($profils[$i]=='ADMIN'){
                    $user = new User();
                }
                elseif($profils[$i]=='FORMATEUR'){
                    $user = new Formateur();
                }
                elseif($profils[$i]=='APPRENANT'){
                    $user = new Apprenant();
                }
                else{
                    $user = new Cm();
                }
                
            $user->setPrenom($faker->firstName)
                 ->setNom($faker->lastName)
                 ->setEmail($faker->safeEmail)
                 ->setAvatar($faker->imageUrl(640,480,'cats'))
                 ->setStatut("Actif")
                 ->setProfil($profil)
                 ->setUsername(strtolower($profils[$i].($j+1)))
                 ->setPassword($this->encoder->encodePassword($user, "password".($j+1)));
                 if($profils[$i]=='APPRENANT'){
                    $user->setGenre($faker->randomElement(['F','M']))
                         ->setStatut($faker->randomElement(['Actif','Attente','Renvoyé']));
                }
            $manager->persist($user);
        }
        }
         
        $manager->flush();
    }
}