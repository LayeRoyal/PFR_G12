<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GroupeController extends AbstractController
{
    /**
    * @Route(
    *     path="api/admin/groupes",
    *     methods={"GET"}
    * )
    */  

    public function listgroup()
    {   
        $tab=['List Groupes'];
        dd($tab);

    }

      /**
    * @Route(
    *     path="api/admin/groupes",
    *     methods={"POST"}
    * )
    */  

    public function savegroupes()
    {   
        $tab=['Save Groupe'];
        dd($tab);

    }

    /**
    * @Route(
    *     path="api/admin/groupes/apprenants",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\groupesController::getApprenantAllGroup",
    *         "__api_resource_class"=Groupe::class,
    *         "__api_collection_operation_name"="get_all_grp_students"
    *     }
    * )
    */  

    public function getApprenantAllGroup()
    {
        $tab=['les apprenants de tous les groupes'];
        dd($tab);

    }

    /**
    * @Route(
    *     path="api/admin/groupes/{id}/apprenants",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\groupesController::getApprenantGroup",
    *         "__api_resource_class"=Groupe::class,
    *         "__api_collection_operation_name"="get_grp_students"
    *     }
    * )
    */  

    public function getApprenantGroup()
    {
        $tab=['les apprenants d\'un groupe'];
        dd($tab);

    }

    /**
    * @Route(
    *     path="api/admin/groupes/{id}/apprenants/{num}",
    *     methods={"DELETE"},
    *     defaults={
    *         "__controller"="\app\Controller\groupesController::delApprenantGroup",
    *         "__api_resource_class"=Groupe::class,
    *         "__api_collection_operation_name"="del_grp_student"
    *     }
    * )
    */  

    public function delApprenantGroup()
    {
        $tab=['Supprimer un apprenant d\'un groupe'];
        dd($tab);

    }

      /**
    * @Route(
    *     path="api/admin/groupes/{id}",
    *     methods={"GET"}
    * )
    */  

    public function detailgroup()
    {   
        $tab=['Detail Groupe'];
        dd($tab);

    }

    /**
    * @Route(
    *     path="api/admin/groupes/{id}",
    *     methods={"PUT"}
    * )
    */  

    public function putgroup()
    {   
        $tab=['Modify Group'];
        dd($tab);

    }

     /**
    * @Route(
    *     path="api/admin/groupes/{id}",
    *     methods={"DELETE"}
    * )
    */  

    public function delgroup()
    {   
        $tab=['Delete Group'];
        dd($tab);

    }

}
