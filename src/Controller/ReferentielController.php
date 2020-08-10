<?php

namespace App\Controller;

use App\Repository\CompetenceRepository;
use App\Repository\ReferentielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReferentielController extends AbstractController
{
    /**
     * @Route(
     * path="api/admin/referentiels/groupe_competences",
     * methods={"GET"},
     * defaults={
     *  "__controller"="App\Controller\ReferentielsController::getGroupeCompetences",
     *          "__api_resource_class"=Referentiel::class,
     *          "__api_collection_operation_name"="get_groupeCompetences"
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

}