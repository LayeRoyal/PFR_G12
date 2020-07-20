<?php

namespace App\DataFixtures;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        // generer les 3 profils et 3 users

        $donnees =  Factory::create('fr_FR');
         for ($i=0; $i <3 ; $i++) { 
            $user = new User();
            $profil= new Profil();
            $user->setPrenom($donnees->firstname)
                 ->setNom($donnees->name)
                 ->setLogin($donnees->username)
                 ->setPassword("test")
                 ->setEmail($donnees->email)
                 ->setAvatar($donnees->text);
            if($i==0){
                $profil->setLibelle("admin");
            }
            elseif($i==1){
                $profil->setLibelle("formateur");
            }else{
                $profil= $profil->setLibelle("cm");
            }
            $user->setProfil($profil);
            
        $manager->persist($profil);
        $manager->persist($user);
         }

        $manager->flush();
    }
}
