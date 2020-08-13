<?php

namespace App\Controller;

use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielRepository;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReferentielController extends AbstractController
{
    /**
     * @Route(
     * path="api/admin/referentiels/groupe_competences",
     * methods={"GET"},
     * defaults={
     *  "__controller"="App\Controller\ReferentielController::getGrpCompet_Ref",
     *          "__api_resource_class"=Referentiel::class,
     *          "__api_collection_operation_name"="grpCompet_Ref"
     * }
     * )
     */
    public function getGroupeCompetences(GroupeCompetenceRepository $repo)
    {
        $groupeCompetence = $repo->findAll();
            return $this->json($groupeCompetence, Response::HTTP_OK);
    }

    /**
     * @Route(
     *     path="api/admin/referentiels/{id}/archivage",
     *     methods={"PUT"},
     *     defaults={
     *         "__controller"="\app\Controller\ReferentielController::putReferentiel",
     *         "__api_resource_class"=Referentiel::class,
     *         "__api_collection_operation_name"="archivage"
     *     }
     * )
     */

    public function putReferentiel(ReferentielRepository $repo, EntityManagerInterface $manager,$id)
    {
        $archivage=$repo->find($id);
        $archivage->setArchivage(true);
        $manager->persist($archivage);
        $manager->flush();
        return $this->json($archivage,Response::HTTP_OK);


    }

    /**
     * @Route(
     * path="api/admin/referentiels/{id}/grpecompetences/{num}",
     * methods={"GET"},
     * defaults={
     *  "__controller"="App\Controller\ReferentielController::getGroupeCompetences",
     *          "__api_resource_class"=Referentiel::class,
     *          "__api_collection_operation_name"="get_groupeCompetences"
     * }
     * )
     */
    public function getGrpCompet_Ref(ReferentielRepository $repoRef, GroupeCompetenceRepository $repoGrp, $id)
    {
        $ref = $repoRef->find($id);
        $groupeCompetences=$ref->getIdCompetences();
        dd($groupeCompetences);
            return $this->json($groupeCompetences, Response::HTTP_OK);
    }


}