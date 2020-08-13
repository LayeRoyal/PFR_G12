<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\NiveauEvaluation;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompetenceController extends AbstractController
{
    /**
     * @Route(
     *     path="/api/admin/competences",
     *     methods={"POST"}
     * )
     */

     public function addCompetence(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $manager)
     {
        $nbr_niveau=3;
        $competence=$request->getContent();
        $tab_compet = $serializer->decode($competence,"json");
        //dd($tab_compet);
        $new_compet= new Competence();
        $new_compet->setLibelle($tab_compet["libelle"])
                   ->setDescriptif($tab_compet["descriptif"]);

        //creation des niveauxEvaluation

        for ($i=1; $i <=$nbr_niveau ; $i++) { 
            $niveau= new NiveauEvaluation();
            $niveau->setLibelle("Niveau $i")
                   ->setCritereEvaluation($tab_compet["niveauEvaluations"][$i]["critereEvaluation"])
                   ->setGroupeAction($tab_compet["niveauEvaluations"][$i]["critereEvaluation"]);
            $manager->persist($niveau);
            $new_compet->addNiveauEvaluation($niveau);
        }
        $manager->persist($new_compet);
        $manager->flush();

        return $this->json($new_compet,Response::HTTP_CREATED);
     }

      /**
    * @Route(
    *     path="api/admin/competences/{id}/archivage",
    *     methods={"PUT"},
    *     defaults={
    *         "__controller"="\app\Controller\CompetenceController::putCompet",
    *         "__api_resource_class"=Competence::class,
    *         "__api_collection_operation_name"="archivage"
    *     }
    * )
    */

        public function putCompet(CompetenceRepository $repo, ValidatorInterface $validator, EntityManagerInterface $manager,$id)
        {
            $archivage=$repo->find($id);
            $archivage->setArchivage(true);
            $manager->persist($archivage);
            $manager->flush();
            return $this->json($archivage,Response::HTTP_OK);


        }
}
