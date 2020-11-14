<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LivrableRenduRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivrableRenduController extends AbstractController
{


      /**
    * @Route(
    *     path="api/apprenants/{id}/livrablepartiels/{num}",
    *     methods={"PUT"}
    * )
    */
   public function putStatut(Request $request,$id,$num,LivrableRenduRepository $repoRendu,SerializerInterface $serializer ,EntityManagerInterface $manager)
   {
       $livrableRendu = $repoRendu -> findOneBy(["apprenant" => $id, "livrablePartiel" => $num]);
         if(!$livrableRendu)
        {
            return $this ->json(["message" => "Apprenant or LivrablePartiel not found"], Response::HTTP_NOT_FOUND);
        }

       $lRendu_json = $request -> getContent();
       $lRendu_tab = $serializer -> decode($lRendu_json,"json");
       $livrableRendu -> setStatut ($lRendu_tab["statut"]);
       $manager -> persist($livrableRendu);
       $manager -> flush();
       $livrableRendu=$serializer->normalize($livrableRendu,null,['groups'=>'lr_read']);
       return $this->json($livrableRendu,Response::HTTP_OK);
   }


    /**
    * @Route(
    *     path="api/formateurs/livrablepartiels/{id}/commentaires",
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\LivrableRenduController::getAllComments",
    *         "__api_resource_class"=LivrableRendu::class,
    *         "__api_collection_operation_name"="getAllComments"
    *     }
    * )
    */
   public function getAllComments($id, SerializerInterface $serializer, LivrableRenduRepository $repoRendu)
   {
       $livrableRendu = $repoRendu -> find($id);
         if(!$livrableRendu)
        {
            return $this ->json(["message" => "LivrableRendu not found"], Response::HTTP_NOT_FOUND);
        }

       $comments=$livrableRendu->getCommentaires();

        $comments=$serializer->normalize($comments,null,['groups'=>'comment_read']);
        // dd($competences);
       return $this->json($comments,Response::HTTP_OK);
   }

}
