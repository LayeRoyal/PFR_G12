<?php

namespace App\Controller;

use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeController extends AbstractController
{
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

    public function getApprenantAllGroup(ApprenantRepository $repo)
    {
        $apprenants=$repo->findAll();
        return $this->json($apprenants,Response::HTTP_OK);
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

    public function getApprenantGroup($id, GroupeRepository $repo)
    {
        $grp=$repo->find($id);
       if(!$grp)
        {
            return $this ->json(["message" => "Group not found"], Response::HTTP_NOT_FOUND);
        }
        $appr=$grp->getApprenants();
        return $this->json($appr,Response::HTTP_OK);
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

    public function delApprenantGroup(GroupeRepository $repo, $id, $num, EntityManagerInterface $manager)
    {
      $grp=$repo->find($id);
       if(!$grp)
        {
            return $this ->json(["message" => "Group not found"], Response::HTTP_NOT_FOUND);
        }
       //supprimer un apprenant d'un grp
        $appr=$grp->getApprenants();
         foreach ($appr as $value) {
             if($value->getId()==$num)
             {
                $grp->removeApprenant($value);
                $manager->persist($grp);
                $manager->flush();
                return $this->json($grp,Response::HTTP_OK);
             }
            
         }
       
        return $this ->json(["message" => "Apprenant not found in this group"], Response::HTTP_NOT_FOUND);
    }

    /**
    * @Route(
    *     path="api/admin/groupes/{id}/apprenants",
    *     methods={"PUT"}
    * )
    */  

    public function addApprenantGroup($id,ApprenantRepository $repoApp, GroupeRepository $repo, Request $request,SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $grp=$repo->find($id);
       if(!$grp)
        {
            return $this ->json(["message" => "Group not found"], Response::HTTP_NOT_FOUND);
        }

        $app=$request->getContent();
        $tab_app = $serializer->decode($app,"json");
        $apprenant= $repoApp->find($tab_app['apprenant']);
        if(!$apprenant)
        {
            return $this ->json(["message" => "Apprenant not found"], Response::HTTP_NOT_FOUND);
        }
        $grp->addApprenant($apprenant);
                $manager->persist($grp);
                $manager->flush();

        return $this->json($grp,Response::HTTP_OK);
    }  

}
