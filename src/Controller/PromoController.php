<?php

namespace App\Controller;

use App\Entity\Promo;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PromoController extends AbstractController
{

    /**
    * @Route(
    *     path="api/admin/promo",
    *     methods={"GET"}
    * )
    */  

    public function listPromo()
    {   
        $tab=['ListPromo'];
        dd($tab);

    }

      /**
    * @Route(
    *     path="api/admin/promo",
    *     methods={"POST"}
    * )
    */  

    public function savePromo()
    {   
        $tab=['SavePromo'];
        dd($tab);

    }


/**
    * @Route(
    *     path="api/admin/promo/principal",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\PromoController::getPromoPrincipal",
    *         "__api_resource_class"=Promo::class,
    *         "__api_collection_operation_name"="detail_grp_principal"
    *     }
    * )
    */

    public function getPromoPrincipal()
    {
        $tab=['PromoPrincipal'];
        dd($tab);  
    }


 /**
    * @Route(
    *     path="api/admin/promo/apprenants/attente",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\PromoController::getAllApprenantAttente",
    *         "__api_resource_class"=Promo::class,
    *         "__api_collection_operation_name"="waiting_list_all_students"
    *     }
    * )
    */  

    public function getAllApprenantAttente()
    {
        $tab=['tous les apprenants en attente'];
        dd($tab);

    }

    /**
    * @Route(
    *     path="api/admin/promo/{id}/principal",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\PromoController::getOneGrpPrincipal",
    *         "__api_resource_class"=Promo::class,
    *         "__api_collection_operation_name"="detail_one_grp_principal"
    *     }
    * )
    */  

    public function getOneGrpPrincipal()
    {
        $tab=['tous les apprenants grp principal'];
        dd($tab);

    }

    /**
    * @Route(
    *     path="api/admin/promo/{id}/referentiels",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\PromoController::getReferentielPromo",
    *         "__api_resource_class"=Promo::class,
    *         "__api_collection_operation_name"="referentiel_promo"
    *     }
    * )
    */  

    public function getReferentielPromo()
    {
        $tab=['Referentiel de la promo'];
        dd($tab);

    }

    /**
    * @Route(
    *     path="api/admin/promo/{id}/apprenants/attente",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\PromoController::getApprenantAttentePromo",
    *         "__api_resource_class"=Promo::class,
    *         "__api_collection_operation_name"="waiting_list_one_promo"
    *     }
    * )
    */  

    public function getApprenantAttentePromo()
    {
        $tab=['les apprenants en attente d\'une promo'];
        dd($tab);

    }

    /**
    * @Route(
    *     path="api/admin/promo/{id}/groupes/{num}/apprenants",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\PromoController::getStudentGrpPromo",
    *         "__api_resource_class"=Promo::class,
    *         "__api_collection_operation_name"="grp_students_of_one_promo"
    *     }
    * )
    */  

    public function getStudentGrpPromo()
    {
        $tab=['tous les apprenants d\'un groupe de la promo'];
        dd($tab);

    }

    /**
    * @Route(
    *     path="api/admin/promo/{id}/formateurs",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\PromoController::getFormateurPromo",
    *         "__api_resource_class"=Promo::class,
    *         "__api_collection_operation_name"="formers_one_promo"
    *     }
    * )
    */  

    public function getFormateurPromo()
    {
        $tab=['tous les formateurs de la promo'];
        dd($tab);

    }

    /**
    * @Route(
    *     path="api/admin/promo/{id}/apprenants",
    *     methods={"PUT"},
    *     defaults={
    *         "__controller"="\app\Controller\PromoController::putApprenantPromo",
    *         "__api_resource_class"=Promo::class,
    *         "__api_collection_operation_name"="add_del_students_one_promo"
    *     }
    * )
    */  

    public function putApprenantPromo()
    {
        $tab=['ajout supp apprenants d\'une promo'];
        dd($tab);

    }

    /**
    * @Route(
    *     path="api/admin/promo/{id}/formateurs",
    *     methods={"PUT"},
    *     defaults={
    *         "__controller"="\app\Controller\PromoController::putFormateurPromo",
    *         "__api_resource_class"=Promo::class,
    *         "__api_collection_operation_name"="add_del_formers_one_promo"
    *     }
    * )
    */  

    public function putFormateurPromo()
    {
        $tab=['ajout supp formateurs d\'une promo'];
        dd($tab);

    }

    /**
    * @Route(
    *     path="api/admin/promo/{id}/groupes/{num}",
    *     methods={"PUT"},
    *     defaults={
    *         "__controller"="\app\Controller\PromoController::putPromoGrpStatus",
    *         "__api_resource_class"=Promo::class,
    *         "__api_collection_operation_name"="put_promo_grp_status"
    *     }
    * )
    */  

    public function putPromoGrpStatus()
    {
        $tab=['Modifier statut d\'un groupe'];
        dd($tab);

    }


      /**
    * @Route(
    *     path="api/admin/promo/{id}",
    *     methods={"GET"}
    * )
    */  

    public function detailPromo()
    {   
        $tab=['DetailPromo'];
        dd($tab);

    }

    /**
    * @Route(
    *     path="api/admin/promo/{id}",
    *     methods={"PUT"}
    * )
    */  

    public function putPromo()
    {   
        $tab=['ModifyPromo'];
        dd($tab);

    }






}
