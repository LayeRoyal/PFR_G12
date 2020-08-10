<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class GroupeCompetenceController extends AbstractController
{
    /**
     * @Route(
     *     path="/api/admin/grpecompetences",
     *     methods={"POST"}
     * )
     */

     public function addGrpeCompetence(CompetenceRepository $repo, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $manager)
     {
        $competence=$request->getContent();
        $tab_grp_compet = $serializer->decode($competence,"json");

         $new_grp_compet= new GroupeCompetence();
           $new_grp_compet->setLibelle($tab_grp_compet["libelle"])
                          ->setDescriptif($tab_grp_compet["descriptif"])
                          ->setArchivage(false);
             foreach ($tab_grp_compet["competence"] as $value) { 
                $competence= $repo->findOneBy(['libelle' => $value]);
                $new_grp_compet->addCompetence($competence);
                }
         $user = $this->get('security.token_storage')->getToken()->getUser();
        $new_grp_compet -> setCreatedBy($user);
        $manager->persist($new_grp_compet);
        $manager->flush();
        return $this->json($new_grp_compet,Response::HTTP_CREATED);

     }


    /**
    * @Route(
    *     path="api/admin/grpecompetences/{id}/archivage",
    *     methods={"PUT"},
    *     defaults={
    *         "__controller"="\app\Controller\GroupeCompetenceController::putGrpCompet",
    *         "__api_resource_class"=GroupeCompetence::class,
    *         "__api_collection_operation_name"="archivage"
    *     }
    * )
    */

        public function putGrpCompet(GroupeCompetenceRepository $repo, ValidatorInterface $validator, EntityManagerInterface $manager,$id)
        {
            $archivage=$repo->find($id);
            $archivage->setArchivage(true);
            $manager->persist($archivage);
            $manager->flush();
            return $this->json($archivage,Response::HTTP_OK);


        }

}
