<?php

namespace App\Controller;

use App\Entity\NiveauEvaluation;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NiveauEvaluationController extends AbstractController
{
    /**
     * @Route(path="/api/niveau_evaluations", methods="post")
     */
    public function creerNiveau(Request $request, CompetenceRepository $repo, EntityManagerInterface $manager)
    {
        $niveau = new NiveauEvaluation();
        $competence = $repo->find($request->request->get('competence'));
        $niveau ->setLibelle($request->request->get('libelle'))
                ->setCritereEvaluation($request->request->get('critereEvaluation'))
                ->setGroupeAction($request->request->get('groupeAction'))
                ->setCompetence($competence);

                $manager->persist($niveau);
                $manager->flush();

                return $this->json($niveau,Response::HTTP_CREATED);

    }
}
