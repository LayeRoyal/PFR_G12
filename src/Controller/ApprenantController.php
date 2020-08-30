<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfilSortieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantController extends AbstractController
{
    /**
     * @Route(
     *     path="/api/apprenants",
     *     methods={"POST"}
     * )
     */
    public function addApprenant(Request $request, \Swift_Mailer $mailer, UserPasswordEncoderInterface $encoder, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $manager)
    {
        $apprenant = $request->request->all();
        $avatar = $request->files->get("avatar");
        $genre=$apprenant['genre'];
        $avatar = fopen($avatar->getRealPath(), "rb");
        $apprenant["avatar"] = $avatar;
        $username = $apprenant['username'];
        $apprenant = $serializer->denormalize($apprenant, "App\Entity\Apprenant");
        $errors = $validator->validate($apprenant);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, "json");
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, [], true);
        }
        function randomPassword($length = 10)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
        $password = randomPassword();
        $apprenant->setPassword($encoder->encodePassword($apprenant, $password))
             ->setGenre($genre);
        $manager->persist($apprenant);
        $manager->flush();
        //Envoi de l'Email de confirmation 

        $message = (new \Swift_Message('Orange Digital Center'))
            ->setFrom('abdoulaye.drame1@uvs.edu.sn')
            ->setTo($apprenant->getEmail())
            ->setBody("mot de passe est $password , pour " . $username);
        $mailer->send($message);

        return  $this->json($apprenant, Response::HTTP_CREATED);
        fclose($avatar);
    }

}
