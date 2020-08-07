<?php

namespace App\Controller;

use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}