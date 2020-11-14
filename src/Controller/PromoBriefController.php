<?php

namespace App\Controller;

use App\Entity\Niveau;
use DateTimeInterface;
use App\Entity\LivrablePartiel;
use App\Repository\PromoBriefRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LivrablePartielRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\NiveauEvaluationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PromoBriefController extends AbstractController
{


     /**
    * @Route(
    *     path="api/formateurs/promo/{id}/brief/{num}/livrablepartiels",
    *     methods={"PUT"}
    * )
    */
    public function putPromoBriefs(PromoBriefRepository $repo,LivrablePartielRepository $repoLP, Request $request, SerializerInterface $serializer , $id, $num, NiveauEvaluationRepository $repniveau, EntityManagerInterface $manager){
        $promobrief = $repo -> findOneBy (["promo" => $id, "brief" => $num]);
         if(!$promobrief)
        {
            return $this ->json(["message" => "Promo Brief not found"], Response::HTTP_NOT_FOUND);
        }
        
        $livrable_json = $request -> getContent();
        $livrable_tab = $serializer -> decode($livrable_json,"json");
        

        // AJOUT LIVRABLE PARTIEL
        if(isset($livrable_tab["livrablePartiels"] )){

            foreach ($livrable_tab["livrablePartiels"] as $key => $value) {
                $livrable = new LivrablePartiel();
                $livrable -> setLibelle ($value["libelle"]);
                $livrable -> setDescription ($value["description"]);
                $livrable -> setDateFin (new \DateTime ($value["delai"]));
                $livrable -> setDateCreation (new \DateTime ());
                $livrable -> setType ($value["type"]);
                foreach ($value["niveaux"] as $val) {
                    
                $niveau = $repniveau -> find($val);
                $livrable -> addNiveau ($niveau);
                }
                $promobrief -> addLivrablePartiel($livrable);
                $manager -> persist($livrable);
                
            }
        }
        // SUPPRESSION LIVRABLE PARTIEL
        if(isset($livrable_tab["delete"] )){

            foreach ($livrable_tab["delete"] as  $value) {
                $delLivrable=$repoLP->find($value);
                $promobrief -> removeLivrablePartiel($delLivrable);
                
            }
        }

            $manager -> persist($promobrief);
            $manager -> flush();

            $promobrief=$serializer->normalize($promobrief,null,['groups'=> ['brief_read',"lr_read","promo_read"] ]);
            return $this->json($promobrief,Response::HTTP_CREATED);

    }


}