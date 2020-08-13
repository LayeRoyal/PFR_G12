<?php

namespace App\Controller;

use DateTime;
use App\Entity\Promo;
use App\Entity\Groupe;
use App\Repository\PromoRepository;
use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PromoController extends AbstractController
{


      /**
    * @Route(
    *     path="api/admin/promo",
    *     methods={"POST"}
    * )
    */  

    public function savePromo(FormateurRepository $repoFormator,ReferentielRepository $repoRef, ApprenantRepository $repoApp, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $manager)
    {
        $promo=$request->getContent();
        $tab_promo = $serializer->decode($promo,"json");
        $new_promo= new Promo();
          $new_promo->setLangue($tab_promo["langue"])
                    ->setTitre($tab_promo["titre"])
                    ->setLieu($tab_promo["lieu"])
                    ->setReferenceAgate($tab_promo["referenceAgate"])
                    ->setEtat($tab_promo["etat"])
                    ->setDescription($tab_promo["description"])
                    ->setDateDebut(new \DateTime($tab_promo["dateDebut"]))
                    ->setDateFinProvisoire(new \DateTime($tab_promo["dateFinProvisoire"]));
            $referentiel= $repoRef->findOneBy(['libelle' => $tab_promo['referentiel']]);
            $new_promo->setReferentiel($referentiel);

            //gestion du drp principal
            $new_Grp_Principal= new Groupe();
            $new_Grp_Principal->setNom($tab_promo['groupePrincipal']["nom"])
                            ->setType($tab_promo['groupePrincipal']["type"])
                            ->setStatut($tab_promo['groupePrincipal']["statut"])
                            ->setNbreApprenant(count($tab_promo["apprenants"]))
                            ->setDateCreation(new \DateTime($tab_promo['groupePrincipal']["dateCreation"]) );
            $manager->persist($new_Grp_Principal);
            foreach ($tab_promo["formateurs"] as $value) {
                $formateur= $repoFormator->find($value);
                $new_promo->addFormateur($formateur);
                $new_Grp_Principal->addFormateur($formateur);
            }
            foreach ($tab_promo["apprenants"] as $value) {
                $apprenant= $repoApp->find($value);
                $new_promo->addApprenant($apprenant);
                $new_Grp_Principal->addApprenant($apprenant);
            }
            $new_promo->addGroupe($new_Grp_Principal);

            $user = $this->get('security.token_storage')->getToken()->getUser();
            $new_promo -> setCreatedBy($user);
            $errors = $validator->validate($new_promo);
            if (count($errors)) {
                $errors = $serializer->serialize($errors, "json");
                return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, [], true);
            }
            $manager->persist($new_promo);
            $manager->flush();
            return $this->json($new_promo,Response::HTTP_CREATED);
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

    public function getReferentielPromo(PromoRepository $repoPromo,$id)
    {
       $promo= $repoPromo->find($id);
        return $this->json($promo,Response::HTTP_OK);


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

    public function getStudentGrpPromo( GroupeRepository $repoGrp, $num)
    {
        $grp= $repoGrp->find($num);
                $apprenants=$grp->getApprenants();
        return $this->json($apprenants,Response::HTTP_OK);
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

    public function getFormateurPromo(PromoRepository $repoPromo,$id, NormalizerInterface $norm)
    {
         $promo= $repoPromo->find($id);
         $formateurs=$promo->getFormateurs();
        dd($formateurs);
         $norm= new ObjectNormalizer();
        //  $formateurNormalises= $norm->normalize($formateurs,null,['groups'=>'promo_read']);
        // return $this->formateurNormalises;
         //$formateurNormalises=$norm->normalize($formateurs,null,['groups'=>'promo_read']);
        return $this->json($formateurs,Response::HTTP_OK);
    }

    /**
    * @Route(
    *     path="api/admin/promo/{id}/apprenants",
    *     methods={"PUT"},
    *     defaults={
    *         "__controller"="\app\Controller\PromoController::patchApprenantPromo",
    *         "__api_resource_class"=Promo::class,
    *         "__api_collection_operation_name"="add_del_students_one_promo"
    *     }
    * )
    */  

    public function putApprenantPromo(Request $req,PromoRepository $repo, ApprenantRepository $apprenant)
    {
        $promo=$repo->find($id);
        $student=$apprenant->find($req->request->get('apprenant'));
        $promo->addApprenant($student);
        
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

    public function putFormateurPromo($id,Request $req, SerializerInterface $serializer,PromoRepository $repo, FormateurRepository $formateur, EntityManagerInterface $manager)
    {
        
       $promo=$repo->find($id);
       $formateurs=$req->getContent();
       $tab_formateur = $serializer->decode($formateurs,"json");
    //    dd($tab_formateur);
    //    foreach ($formateurs as $value) {  
    //    }
        foreach ($tab_formateur['formateurs'] as $value) {
            $form=$formateur->find($value);
            $promo->addFormateur($form);
        }
        $manager->flush();
        return $this->json($promo,Response::HTTP_OK);

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

    public function detailPromo( PromoRepository $repo,$id)
    {   
        $promo=$repo->find($id);
        return $this->json($promo,Response::HTTP_OK);
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
