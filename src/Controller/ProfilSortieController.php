<?php

namespace App\Controller;


use App\Entity\ProfilSortie;
use App\Repository\PromoRepository;
use App\Repository\ProfilRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfilSortieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilSortieController extends AbstractController
{
    
      /**
     * @Route(
     *      path="api/admin/profilSorties/{id}/archivage",
     *      methods={"put"},
     *      defaults={
     *          "__controller"="App\Controller\ProfilSortieController::archiveProfilSorties",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="archive"
     * }
     * )
     */


    public function archiveProfilSorties(ProfilSortieRepository $repo, EntityManagerInterface $em, $id)
    {
        $archivage = $repo->find($id);
        $archivage->setArchivage(true);
        $em->persist($archivage);
        $em->flush();
        return $this->json($archivage,Response::HTTP_OK);

    }


      /**
     * @Route(
     *      path="api/admin/promo/{id}/profilSorties",
     *      methods={"GET"},
     *      defaults={
     *          "__controller"="App\Controller\ProfilSortieController::getApprenantPromoProfilSorties",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="ApprenantPromoProfilSorties"
     * }
     * )
     */

    // public function getApprenantPromoProfilSorties($id, ApprenantRepository $reposApprenant, ProfilSortieRepository $repoProfilSortie, PromoRepository $repoPromo)
    // {
    //     //$promo = $repoPromo->find($id);
    //     $apprenantByProfilSortie = $repoProfilSortie->findAll();
    //     // foreach($profilSortie->getApprenants() as $profilsorti){
    //     //     if($apprenantByProfilSortie->getPromo()->getId()==$id)
    //     //     {
    //     //         $grpApprenant=[$profilSorti => [$apprenant]];
    //     //     }
    //     // }  

    //     return $this->json($apprenantByProfilSortie, Response::HTTP_OK);
    // }


    public function getApprenantPromoProfilSorties($id, ApprenantRepository $reposApprenant, ProfilSortieRepository $repoProfilSortie, PromoRepository $repoPromo)
    {
        $promo = $repoPromo->find($id);
        $profilSortie = $repoProfilSortie->findAll();
        $tab_apprenant = [];
        foreach($profilSortie as $key=>$value){
            foreach($value->getApprenants() as $apprenant){
                if($apprenant->getPromo()->getId()==$id)
                {
                $tab_apprenant[]=$apprenant;
                }
            }  
        }  
        return $this->json($tab_apprenant, Response::HTTP_OK);
    }


      /**
     * @Route(
     *      path="api/admin/profilSorties",
     *      methods={"POST"},
     *      defaults={
     *          "__controller"="App\Controller\ProfilSortieController::addProfilSorties",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="addProfilSorties"
     * }
     * )
     */
    public function addProfilSorties(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $manager)
    {
        $profilSortie = $request->getContent();
        $tab_profilSortie = $serializer->decode($profilSortie, "json");
        $new_profilSortie = new ProfilSortie();
        $new_profilSortie->setLibelle($tab_profilSortie["libelle"]);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $new_profilSortie -> setCreatedBy($user);
        $new_profilSortie ->setArchivage(0);

            $manager->persist($new_profilSortie);
            $manager->flush();
            return $this->json($new_profilSortie,Response::HTTP_CREATED);
    }     



      /**
     * @Route(
     *      path="api/admin/profilSorties",
     *      methods={"GET"},
     *      defaults={
     *          "__controller"="App\Controller\ProfilSortieController::getProfilSorties",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="profilSorties"
     * }
     * )
     */
    public function getProfilSorties(ProfilSortieRepository $repoPro)
    {
        $profilSortie = $repoPro->findBy(['archivage'=>false]);
        return $this->json($profilSortie, Response::HTTP_OK);

    } 
    
    

      /**
     * @Route(
     *      path="api/admin/profilSorties/{id}",
     *      methods={"GET"},
     *      defaults={
     *          "__controller"="App\Controller\ProfilSortieController::getProfilSortieApprenant",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="profilSortieApprenant"
     * }
     * )
     */
    public function getProfilSortieApprenant(ProfilSortieRepository $repoProAp, $id)
    {
        $profilSortieAp = $repoProAp->findOneBy(['id'=>$id]);
        //dd($profilSortieAp);
        return $this->json($profilSortieAp, Response::HTTP_OK);
    } 


          /**
     * @Route(
     *      path="api/admin/profilSorties/{id}",
     *      methods={"PUT"},
     *      defaults={
     *          "__controller"="App\Controller\ProfilSortieController::updateProfilSortie",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="updateProfilSortie"
     * }
     * )
     */
    public function updateProfilSortie(ProfilSortieRepository $reposPro, EntityManagerInterface $manager,$id, Request $request)
    {
        $profilSortie = $reposPro->find($id);
        $data =json_decode($request->getContent(),true);
        $profilSortie->setLibelle($data["libelle"]);
            $manager->flush();
            return $this->json($profilSortie,Response::HTTP_OK);
    } 



          /**
     * @Route(
     *      path="api/admin/promo/{id}/profilSorties/{num}",
     *      methods={"GET"},
     *      defaults={
     *          "__controller"="App\Controller\ProfilSortieController::getlistApprenantProfilSortiesParPromo",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="list_apprenant_profil_promo"
     * }
     * )
     */
    public function getlistApprenantProfilSortiesParPromo($id, $num, ProfilSortieRepository $reposPro, PromoRepository $reposPromo, ApprenantRepository $reposApprenant)
    {
        $apprenants = $reposApprenant->findBy(["promo"=>$id,"profilSortie"=>$num]);
        return $this->json($apprenants, Response::HTTP_OK);
    } 
}
