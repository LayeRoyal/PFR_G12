<?php

namespace App\Controller;

use App\Entity\ProfilSortie;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilSortieController extends AbstractController
{
    /**
     * @Route(
     * path="api/admin/promo/id/profilsorties",
     * methods="{"GET"},
     * defaults={
     *         "__controller"="\app\Controller\ProfilSortieController::getApprenantPromoProfilSorties",
     *         "__api_resource_class"=ProfilSortie::class,
     *         "__api_collection_operation_name"="list_aprenant_promo_profilsorties"    
     * }
     * )
     **/

    /**public function getApprenantPromoProfilSorties(ApprenantRepository $repo)
    {
        $apprenantPromoProfilSortie=$repo->findBy($id);
        return $this->json($apprenantPromoProfilSortie, Response::HTTP_OK); 
    }
    */



        /**
     * @Route(
     *     path="api/admin/profilsortie/{id}/archivage",
     *     methods={"PUT"},
     *     defaults={
     *         "__controller"="\app\Controller\ProfilSortieController::putProfilSortie",
     *         "__api_resource_class"=ProflSortie::class,
     *         "__api_collection_operation_name"="archivage"
     *     }
     * )
     */

    public function putProfilSortie(ProfilSortie $repos, EntityManagerInterface $manager,$id)
    {
        $archivage=$repos->find($id);
        $archivage->setArchivage(true);
        $manager->persist($archivage);
        $manager->flush();
        return $this->json($archivage,Response::HTTP_OK);

    }

}
