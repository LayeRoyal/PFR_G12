<?php

namespace App\Controller;

use App\Entity\LivrableAttendu;
use App\Repository\BriefRepository;
use App\Entity\LivrableAttenduApprenant;
use App\Repository\PromosRepository;
use App\Repository\BriefApprenantRepository;
use App\Repository\BriefMaPromoRepository;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivrableController extends AbstractController
{
    /**
     * @Route(
     *      path="/api/apprenants/{id_appr}/promo/{id_promo}/briefs/{id_brief}",
     *      methods={"GET"}
     * )
     */
    public function getBriefs(
        BriefRepository $briefRepository,
        PromosRepository $promosRepository,
        ApprenantRepository $apprenantRepository,
        BriefMaPromoRepository $briefMaPromoRepository,
        BriefApprenantRepository $briefApprenant,
        EntityManagerInterface $em,
        $id_appr,
        $id_promo,
        $id_brief
    ) {
        if (!$id_appr || !$id_promo || !$id_brief) {

            return $this->json(["message" => "Ressource n existe pas"], Response::HTTP_NOT_FOUND);
        }

        $apprenant = $apprenantRepository->findOneById($id_appr);
        $promo = $promosRepository->findOneById($id_promo);
        $brief = $briefRepository->findOneById($id_brief);
        if (!$apprenant || !$promo || !$brief) {
            return $this->json(["message" => "Ressource n existe pas"], Response::HTTP_NOT_FOUND);
        }
        //methode 1

        $briefapprenant = $apprenant->getBriefApprenant();
        $briefMaPromo = $briefMaPromoRepository->findOneBy(['promo' => $promo, 'brief' => $brief, 'briefapprenant' => $briefapprenant]);
        if (!$briefMaPromo) {
            return $this->json(["message" => "Ressource n existe pas"], Response::HTTP_NOT_FOUND);
        }
        $livrabePartiel = $briefMaPromo->getLivrablePartiels();
        if (!$livrabePartiel) {
            return $this->json(["message" => "Ressource n existe pas"], Response::HTTP_NOT_FOUND);
        }
        return $this->json($livrabePartiel, Response::HTTP_OK);
    }


    /**
     * @Route(
     *      path="/api/apprenants/{id_app}/groupe/{id_groupe}/livrables",
     *      methods={"POST"}
     * )
     */
    public function sendLivrable(
        EntityManagerInterface $em,
        Request $request,
        SerializerInterface $serializer
    ) {

        if (!empty($request->getContent())) {
            $data = $request->getContent();
        } else {
            $data = $request->request->all();
        }
        $data = $serializer->decode($data, "json");
        $livrableAttendu = new LivrableAttendu();
        if (!empty($data['libelle'])) {
            $livrableAttendu->setLibelle($data['libelle']);
        }
        if (!empty($data['description'])) {
            $livrableAttendu->setDescription($data['description']);
        }

        $apprenant = $this->get('security.token_storage')->getToken()->getUser();
        if ($data['livrableAttenduApprenants']) {
            $livrableAttenduApprenant = new LivrableAttenduApprenant();
            //si on a une chaine str de url separer par des virgules;
            if (gettype($data['livrableAttenduApprenants']) == "string") {
                $urls = explode(",", $data['livrableAttenduApprenants']);
            } elseif (gettype($data['livrableAttenduApprenants']) == "array") {
                $urls = $data['livrableAttenduApprenants'];
            }

            if ($urls) {
                foreach ($urls as $url) {
                    $livrableAttenduApprenant->setUrl($url);
                    $livrableAttenduApprenant->addApprenant($apprenant);
                    $em->persist($livrableAttenduApprenant);
                    $livrableAttendu->addLivrableAttenduApprenant($livrableAttenduApprenant);
                }
                $em->persist($livrableAttendu);
                $em->flush();

                return $this->json($livrableAttendu, Response::HTTP_CREATED);
            } elseif ($data['livrableAttenduApprenants'] instanceof LivrableAttenduApprenant) {
                //si c est une instance LivrableAttenduApprenant
                $livrableAttendu->addLivrableAttenduApprenant($data['livrableAttenduApprenants']);
                $em->persist($livrableAttendu);
                $em->flush();
                return $this->json($livrableAttendu, Response::HTTP_CREATED);
            }
        }
    }
}
