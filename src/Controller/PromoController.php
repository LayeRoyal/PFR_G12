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
    *         "__api_collection_operation_name"="list_grp_principal"
    *     }
    * )
    */

      public function getPromoPrincipal(GroupeRepository $repo)
        {
            $promoPrincipal=$repo->findBy(["type"=>"Principal"]);
                return $this->json($promoPrincipal, Response::HTTP_OK); 
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

     public function getAllApprenantAttente(ApprenantRepository $apprenants)
    {
        $apprenantsEnAttente = $apprenants->findBy(['statut'=>'Attente']);
        return $this->json($apprenantsEnAttente,Response::HTTP_OK);

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

     public function getOneGrpPrincipal(GroupeRepository $repo,$id)
        {        
            $detailGroupePrincipal=$repo->findBy(["type"=>"Principal", "promo"=>$id]);
                return $this->json($detailGroupePrincipal, Response::HTTP_OK);

        }

    /**
    * @Route(
    *     path="api/admin/promo/{id<[0-9]+>}/referentiels",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\PromoController::getReferentielPromo",
    *         "__api_resource_class"=Promo::class,
    *         "__api_collection_operation_name"="referentiel_promo"
    *     }
    * )
    */  

    public function getReferentielPromo(PromoRepository $repoRef,$id)
    {
       $ref= $repoRef->find($id)->getReferentiel();
        return $this->json($ref,Response::HTTP_OK);
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

     public function getApprenantAttentePromo(ApprenantRepository $apprenant, $id)
    {
       $apprenantEnAttente = $apprenant->findBy(['promo'=>$id, 'statut'=>'Attente' ]);
       return $this->json($apprenantEnAttente,Response::HTTP_OK);

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

    public function getStudentGrpPromo( PromoRepository $repoPromo, GroupeRepository $repoGrp, $id, $num)
    {
        $promo = $repoPromo->find($id);
         if(!$promo)
        {
            return $this ->json(["message" => "Promo not found"], Response::HTTP_NOT_FOUND);
        }
        
        $grpPromo=$promo->getGroupes();
        $check=false;
        foreach ($grpPromo as $value) {
            if($value->getId()==$num)
            {
                $check=true;
            break;
            }
        }
        if($check==false)
        {
            return $this ->json(["message" => "Group not found in this promo"], Response::HTTP_NOT_FOUND);
        }
        $grp= $repoPromo->find($num);
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

    public function getFormateurPromo(PromoRepository $repoPromo,$id, EntityManagerInterface $manager)
    {
         $promo= $repoPromo->find($id);
             if(!$promo)
            {
                return $this ->json(["message" => "Promo not found"], Response::HTTP_NOT_FOUND);
            }
         $formateurs=$promo->getFormateurs();
        return $this->json($formateurs,Response::HTTP_OK);
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

     public function putApprenantPromo($id,Request $req, SerializerInterface $serializer,PromoRepository $repo, ApprenantRepository $appr, EntityManagerInterface $manager)
    {
       $promo=$repo->find($id);
        if(!$promo)
        {
            return $this ->json(["message" => "Promo not found"], Response::HTTP_NOT_FOUND);
        }
       //supprimer ts les apprenants pour ajouter ce qu'on veut
        $apprenant=$promo->getApprenants();
         foreach ($apprenant as $value) {
            $promo->removeApprenant($value);
         }
       
       $apprenants=$req->getContent();
       $tab_apprenant = $serializer->decode($apprenants,"json");
    
        foreach ($tab_apprenant['apprenants'] as $value) {
            $form=$appr->find($value);
            $form->setPromo($promo);
         $manager->persist($form);
        }
         $manager->persist($promo);
        $manager->flush();
        return $this->json($promo,Response::HTTP_OK);

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
        if(!$promo)
        {
            return $this ->json(["message" => "Promo not found"], Response::HTTP_NOT_FOUND);
        }
       //supprimer ts les formateurs pour ajouter ce qu'on veut
        $formateurs=$promo->getFormateurs();
         foreach ($formateurs as $value) {
            $promo->removeFormateur($value);
         }
       
       $formateurs=$req->getContent();
       $tab_formateur = $serializer->decode($formateurs,"json");
    
        foreach ($tab_formateur['formateurs'] as $value) {
            $form=$formateur->find($value);
            $promo->addFormateur($form);
        }
         $manager->persist($promo);
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

    
    public function putPromoGrpStatus(PromoRepository $repo, GroupeRepository $reposi, EntityManagerInterface $manager, Request $request, SerializerInterface $serializer,$id,$num)
    {
        $promo=$repo->find($id);
        if(!$promo)
        {
            return $this ->json(["message" => "Promo not found"], Response::HTTP_NOT_FOUND);
        }
        $grpPromo=$promo->getGroupes();
        $check=false;
        foreach ($grpPromo as $value) {
            if($value->getId()==$num)
            {
                $check=true;
            break;
            }
        }
        if($check==false)
        {
            return $this ->json(["message" => "Group not found in this promo"], Response::HTTP_NOT_FOUND);
        }
        //apres verification on change le statut
        $groupe=$reposi->find($num);

        $groupe=$request->getContent();
        $tab_groupe = $serializer->decode($groupe,"json");

        $groupe->setStatut($tab_groupe['statut']);
        $manager->persist($groupe);
        $manager->flush();
            return $this->json($groupe, Response::HTTP_OK);
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

  
}
