<?php

namespace App\Controller;

use App\Repository\PromoRepository;
use App\Repository\ApprenantRepository;
use App\Repository\ReferentielRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StatisticCompetenceRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatisticCompetenceController extends AbstractController
{
     /**
    * @Route(
    *     path="api/formateurs/promo/{id}/referentiel/{num}/competences",
    *     methods={"GET"}
    * )
    */
   public function getAllStatsCompet($id,$num, SerializerInterface $serializer, PromoRepository $repo)
   {
        $promoRef=$repo->findOneBy(["id"=>$id,"referentiel"=>$num]);
        
        if(!$promoRef)
        {
            return $this ->json(["message" => "Promo Referentiel not found"], Response::HTTP_NOT_FOUND);
        }
        $apprenants=$promoRef->getApprenants();
        $apprenants=$serializer->normalize($apprenants,null,['groups'=>'stats_read']);
        

       return $this->json($apprenants,Response::HTTP_OK);
   }

    /**
    * @Route(
    *     path="api/formateurs/promo/{idPr}/referentiel/{idRef}/statistiques/competences",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\StatisticCompetenceController::getAllCompet",
    *         "__api_resource_class"=StatisticCompetence::class,
    *         "__api_collection_operation_name"="compet_stats"
    *     }
    * )
    */
       
     public function getAllCompet($idPr,$idRef, SerializerInterface $serializer, StatisticCompetenceRepository $repoSt, ReferentielRepository $repoRef, PromoRepository $repoPromo)
   {
       $promoRef=$repoPromo->findBy(["id"=>$idPr,"referentiel"=>$idRef]);
        if(!$promoRef)
        {
            return $this ->json(["message" => "Promo Referentiel not found"], Response::HTTP_NOT_FOUND);
        }
        $ref=$repoRef->find($idRef);
        $grpCompet=$ref->getGroupeCompetences();
        // dd($grpCompet);
        $i=1;
        foreach ($grpCompet as $key) {
            $competences=$key->getCompetence();
            foreach ($competences as $key) {
            $tab["competence $i"]=$serializer->normalize($key,null,['groups'=>'sc_read']);
            $niveau1=$repoSt->findBy(["niveau1"=>true,"competence"=>$key]);
            $niveau2=$repoSt->findBy(["niveau2"=>true,"competence"=>$key]);
            $niveau3=$repoSt->findBy(["niveau3"=>true,"competence"=>$key]);
            $niveaux=["niveau 1"=>count($niveau1),"niveau 2"=>count($niveau2),"niveau 3"=>count($niveau3)];
            $tab["competence $i"]["niveaux"]=$niveaux;
            $i++;
            }
        }

       return $this->json($tab,Response::HTTP_OK);
   }

   /**
    * @Route(
    *     path="api/apprenant/{idAp}/promo/{idPr}/referentiel/{idRef}/competences",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\StatisticCompetenceController::getAppCompet",
    *         "__api_resource_class"=StatisticCompetence::class,
    *         "__api_collection_operation_name"="apprenant_compet"
    *     }
    * )
    */

    public function getAppCompet($idPr,$idRef,$idAp,SerializerInterface $serializer,  ApprenantRepository $repo)
    {
            $apprenant=$repo->findOneBy(["promo"=>$idPr,"id"=>$idAp]);    
           
            //verifier si promoRef existe
            $ref=$apprenant->getPromo()->getReferentiel()->getId();
            if($ref!=$idRef)
            {
                return $this ->json(["message" => "Aprrenant not found dans Promo Referentiel "], Response::HTTP_NOT_FOUND);
            }
        $apprenant=$serializer->normalize($apprenant,null,['groups'=>'stats_read']);

        return $this->json($apprenant,Response::HTTP_OK);
    }


    /**
    * @Route(
    *     path="api/apprenant/{idAp}/promo/{idPr}/referentiel/{idRef}/statistiques/briefs",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\StatisticCompetenceController::getAppBrief",
    *         "__api_resource_class"=StatisticCompetence::class,
    *         "__api_collection_operation_name"="apprenant_brief"
    *     }
    * )
    */

    public function getAppBrief($idPr,$idRef,$idAp,SerializerInterface $serializer,  ApprenantRepository $repo)
    {
            $apprenant=$repo->findOneBy(["promo"=>$idPr,"id"=>$idAp]);    
           
            //verifier si promoRef existe
            $ref=$apprenant->getPromo()->getReferentiel()->getId();
            if($ref!=$idRef)
            {
                return $this ->json(["message" => "Aprrenant not found dans Promo Referentiel "], Response::HTTP_NOT_FOUND);
            }

        $apprenant=$serializer->normalize($apprenant,null,['groups'=>['brief_read','user_details_read']]);

        return $this->json($apprenant,Response::HTTP_OK);
    }
}
