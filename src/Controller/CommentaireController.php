<?php

namespace App\Controller;

use DateTimeInterface;
use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LivrableRenduRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{


        /**
    * @Route(
    *     path="api/formateurs/livrablepartiels/{id}/commentaires",
    *     methods={"POST"},
    *        defaults={
    *         "__controller"="\app\Controller\CommentaireController::addFormateurComment",
    *         "__api_resource_class"=Commentaire::class,
    *         "__api_collection_operation_name"="postedBFormateur"
    *     }
    * )
    * @IsGranted("ROLE_FORMATEUR", statusCode=403, message="Seul un formateur peut faire cette action.")
    */
    public function addFormateurComment($id, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager , LivrableRenduRepository $repolr)
    {
        $livrableRendu = $repolr -> find($id);  
        if(!$livrableRendu)
        {
            return $this ->json(["message" => "LivrablePartiel not found"], Response::HTTP_NOT_FOUND);
        }
        
        $formateur = $this->get('security.token_storage')->getToken()->getUser();

        //form data ou raw
            if (!empty($request->getContent())) {
            $commentaires_json = $request->getContent();
             $commentaires_tab = $serializer -> decode($commentaires_json,"json");
            } 
            else {
            $commentaires_tab = $request->request->all();
            $pieceJointe = $request->files->get("pieceJointe");
                if ($pieceJointe) {
                    $pieceJointe = fopen($pieceJointe->getRealPath(), "rb");
                    $commentaires_tab["pieceJointe"] = $pieceJointe;
                }
            }
        // libelle ou piece jointe
        $commentaire = new Commentaire () ;
        if(isset ($commentaires_tab["libelle"]))
        {
        $commentaire -> setLibelle($commentaires_tab["libelle"]);
        } else 
        {
            $commentaire -> setPieceJointe($commentaires_tab["pieceJointe"]);
        }
        
        

        $commentaire -> setCreatedAt(new \DateTime());

        $commentaire -> setLivrableRendu($livrableRendu);
        $commentaire -> setFormateur($formateur);
        $manager -> persist($commentaire);
        $manager -> flush();

        return $this->json($commentaire,Response::HTTP_CREATED);

    }


        /**
    * @Route(
    *     path="api/apprenants/livrablepartiels/{id}/commentaires",
    *     methods={"POST"},
    *        defaults={
    *         "__controller"="\app\Controller\CommentaireController::addApprenantComment",
    *         "__api_resource_class"=Commentaire::class,
    *         "__api_collection_operation_name"="postedByApprenant"
    *     }
    * )
    * @IsGranted("ROLE_APPRENANT", statusCode=403, message="Seul un apprenant peut faire cette action.")
    */
    public function addApprenantComment($id, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager , LivrableRenduRepository $repolr)
    {
        $livrableRendu = $repolr -> find($id);  
        if(!$livrableRendu)
        {
            return $this ->json(["message" => "LivrablePartiel not found"], Response::HTTP_NOT_FOUND);
        }

        //form data ou raw
            if (!empty($request->getContent())) {
            $commentaires_json = $request->getContent();
             $commentaires_tab = $serializer -> decode($commentaires_json,"json");
            } 
            else {
            $commentaires_tab = $request->request->all();
            $pieceJointe = $request->files->get("pieceJointe");
                if ($pieceJointe) {
                    $pieceJointe = fopen($pieceJointe->getRealPath(), "rb");
                    $commentaires_tab["pieceJointe"] = $pieceJointe;
                }
            }
        // libelle ou piece jointe
        $commentaire = new Commentaire () ;
        if(isset ($commentaires_tab["libelle"]))
        {
        $commentaire -> setLibelle($commentaires_tab["libelle"]);
        } else 
        {
            $commentaire -> setPieceJointe($commentaires_tab["pieceJointe"]);
        }

        $commentaire -> setCreatedAt(new \DateTime());

        $commentaire -> setLivrableRendu($livrableRendu);
        $manager -> persist($commentaire);
        $manager -> flush();

        return $this->json($commentaire,Response::HTTP_CREATED);

    }


}