<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Entity\PromoBrief;
use App\Repository\BriefRepository;
use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use App\Repository\PromoBriefRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BriefController extends AbstractController
{
    
/**
    * @Route(
    *     name="ListeBriefs",  
    *     path="api/formateurs/briefs",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\BriefController::getAllBriefs",
    *         "__api_resource_class"=Brief::class,
    *         "__api_collection_operation_name"="ListBriefs"
    *     }
    * )
    */
   
     public function getAllBriefs(BriefRepository $repoBrief){
        $briefs = $repoBrief->findAll();
        return $this->json($briefs, Response::HTTP_OK); 
     }


/**
    * @Route(
    *     name="ListeBriefsPromoGroup",  
    *     path="api/formateurs/promo/{id}/groupe/{num}/briefs",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\BriefController::getAllBriefsPromoGroupe",
    *         "__api_resource_class"=Brief::class,
    *         "__api_collection_operation_name"="ListBriefsGroupPromo"
    *     }
    * )
    */

    public function getAllBriefsPromoGroupe(GroupeRepository $repoGrp, $id, $num){
        $groupe = $repoGrp->getGroup($id, $num);
        return $this->json($groupe, Response::HTTP_OK,["groups"=>"briefPromo:read"]);  
    }



/**
    * @Route(
    *     name="ListeBriefsBr",  
    *     path="api/formateurs/{id}/briefs/brouillons",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\BriefController::getAllBriefsBrouillons",
    *         "__api_resource_class"=Brief::class,
    *         "__api_collection_operation_name"="ListBriefsBrouillons"
    *     }
    * )
    */

    public function getAllBriefsBrouillons(FormateurRepository $repoFormateur, $id){
        $formateur = $repoFormateur->find($id);
        $brouillons = $formateur->getBriefs();
        $brouillon=[];
       
        foreach ($brouillons as $key => $value) {
            if($value->getStatut()=="Brouillon"){
                $brouillon[]= $value;
            }
        }
        return $this->json($brouillon, Response::HTTP_OK); 
    }


/**
    * @Route(
    *     name="ListeBriefsVal",  
    *     path="api/formateurs/{id}/briefs/valides",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\BriefController::getAllBriefsValides",
    *         "__api_resource_class"=Brief::class,
    *         "__api_collection_operation_name"="ListBriefsValides"
    *     }
    * )
    */

    public function getAllBriefsValides(FormateurRepository $repoFormateur, $id){
        $formateur = $repoFormateur->find($id);
        $valides = $formateur->getBriefs();
        $valide=[];
       
        foreach ($valides as $key => $value) {
            if($value->getStatut()=="Valide" || $value->getStatut()=="Assigne"){
                $valide[]= $value;
            }
        }
        return $this->json($valide, Response::HTTP_OK); 
    }


/**
    * @Route(
    *     name="ListeBriefsPr",  
    *     path="api/formateurs/promo/{id}/briefs",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\BriefController::getAllBriefsPromo",
    *         "__api_resource_class"=PromoBrief::class,
    *         "__api_collection_operation_name"="ListBriefsPromo"
    *     }
    * )
    */

        public function getAllBriefsPromo(PromoBriefRepository $repoPromoBr, $id){
            $briefs=$repoPromoBr->getAllBriefs($id);
            return $this->json($briefs, Response::HTTP_OK); 

        }


/**
    * @Route(
    *     name="ListeBriefsOnePr",  
    *     path="api/formateurs/promo/{id}/briefs/{num}",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\BriefController::getOneBriefPromo",
    *         "__api_resource_class"=PromoBrief::class,
    *         "__api_item_operation_name"="BriefPromo"
    *     }
    * )
    */

    public function getOneBriefPromo(PromoBriefRepository $repoPromoBr, $id, $num){
        $briefs=$repoPromoBr->getOneBrief($id,$num);
        return $this->json($briefs, Response::HTTP_OK); 

    }



/**
    * @Route(
    *     name="ListeBriefsAss",  
    *     path="api/apprenants/promo/{id}/briefs",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\BriefController::getAllBriefsApprenant",
    *         "__api_resource_class"=PromoBrief::class,
    *         "__api_collection_operation_name"="ListBriefsAssigne"
    *     }
    * )
*/

    public function getAllBriefsApprenant(ApprenantRepository $repoAppr,$id){
       
       $promo = $repoAppr->findBy(["promo"=>$id]);
      
       return $this->json($promo, Response::HTTP_OK,["groups"=>"briefAssign:read"]); 
     
    }


/**
    * @Route(
    *     name="BriefParFormateur",  
    *     path="api/formateur/{id}/promo/{p}/brief/{b}",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\BriefController::getBriefFormateur",
    *         "__api_resource_class"=Brief::class,
    *         "__api_collection_operation_name"="FormateurBrief"
    *     }
    * )
*/

        public function getBriefFormateur(){
           
         
        }


/**
    * @Route(
    *     name="ModifBrief",  
    *     path="api/formateurs/promo/{id}/brief/{num}",
    *     methods={"PUT"},
    *     defaults={
    *         "__controller"="\app\Controller\BriefController::putBrief",
    *         "__api_resource_class"=Brief::class,
    *         "__api_collection_operation_name"="PutBrief"
    *     }
    * )
*/

        public function putBrief(){

        }

/**
    * @Route(
    *     name="ModifBriefAss",  
    *     path="api/formateurs/promo/{id}/brief/{num}/assignation",
    *     methods={"PUT"},
    *     defaults={
    *         "__controller"="\app\Controller\BriefController::putBriefAssigner",
    *         "__api_resource_class"=Brief::class,
    *         "__api_collection_operation_name"="PutBriefAssigner"
    *     }
    * )
*/

    public function putBriefAssigner(){

    }
}
