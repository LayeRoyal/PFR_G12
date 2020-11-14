<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Entity\PromoBrief;
use App\Entity\LivrableAttendu;
use App\Entity\PromoBriefApprenant;
use App\Repository\BriefRepository;
use App\Repository\PromoRepository;
use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use App\Repository\PromoBriefRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BriefController extends AbstractController
{
    private $_em;
    private $_serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->_em = $em;
        $this->_serializer = $serializer;
    }

    /**
     * @Route(
     *      path="api/formateurs/promo/{id_promo}/brief/{id_brief}",
     *      requirements={"id_forma"="\d+","id_promo"="\d+", "id_brief"="\d+" },
     *      methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\BriefController::getBriefInPromo",
    *         "__api_resource_class"=Brief::class,
    *         "__api_collection_operation_name"="briefInPromo"
    *     }   
     * )
     */
    public function getBriefInpromo(
        BriefRepository $briefRepository,
        PromoRepository $promoRepository,
        FormateurRepository $formateurRepository,
        PromoBriefRepository $promoBriefRepository,
        $id_promo,
        $id_brief
    ) {
        /*
        * Cette methode recupere un brief qu'un formateur a cree dans un promo 
        */
// dd('jjkdhjkhjkh');
        if ( !$id_promo || !$id_brief) {

            return $this->json(["message" => "La demande n'a pas été trouvée"], Response::HTTP_NOT_FOUND);
        }
        $promo = $promoRepository->find($id_promo);
        $brief = $briefRepository->find($id_brief);
        $formateur = $this->get('security.token_storage')->getToken()->getUser();
        //$formateur = $formateurRepository->find(['id' => $id_forma, 'briefs' => $brief]);
        //dd($formateur);
        if (!$formateur || !$promo || !$brief) {
            return $this->json(["message" => "La demande n'a pas été trouvée"], Response::HTTP_NOT_FOUND);
        }
        $briefMaPromo = $promoBriefRepository->findOneBy(['promo' => $promo, 'brief' => $brief]);

        if (!$briefMaPromo) {
            return $this->json(["message" => "Non trouver"], Response::HTTP_NOT_FOUND);
        }


        //si le brief est créé par ce formateur passer en parm url
        if ($brief->getCreatedBy() == $formateur) {

            return $this->json($brief, Response::HTTP_OK);
        } else {
            return $this->json(["message" => "brief non créé par ce formateur"], Response::HTTP_NOT_FOUND);
        }
    }

 

    /**
     * @Route(
     *      path="/api/formateurs/briefs",
     *      methods={"POST"}
     * )
     */
    public function add(Request $request, ValidatorInterface $validator)
    {
        /*
        * Cette methode permet d'ajout un brief
        */

        //On fait une verification pour recuperer les donnees par 
        // => request->request ou request->getContent 
        if (!empty($request->getContent())) {
            $data = $this->_serializer->decode($request->getContent(), "json");
        } else {
            $data = $request->request->all();
            $avatar = $request->files->get("avatar");
            if ($avatar) {
                $avatar = fopen($avatar->getRealPath(), "rb");
                $data["avatar"] = $avatar;
            }
        }

        $brief = $this->_serializer->denormalize($data, "App\Entity\Brief");
        $errors = $validator->validate($brief);
        if (count($errors)) {
            $errors = $this->_serializer->serialize($errors, "json");
            return $this->json('Erreur', Response::HTTP_BAD_REQUEST);
        }
        $formateur = $this->get('security.token_storage')->getToken()->getUser();

        $brief->setCreatedBy($formateur);
        $this->_em->persist($brief);
        $this->_em->flush();
        return $this->json($brief, Response::HTTP_CREATED);
    }



    /**
     * @Route(
     *      path="/api/formateurs/briefs/{id}",
     *      methods={"POST"},
    *     defaults={
    *         "__controller"="\app\Controller\BriefController::copyBrief",
    *         "__api_resource_class"=Brief::class,
    *         "__api_collection_operation_name"="duplicate"
    *     }
     * )
     */
    public function copyBrief(Brief $brief)
    {
        /*
        * Cette methode permet de duplicer un brief
        */

        $brief->setDateCreation(new \DateTime());

        //clone le brief (object)
        $briefcopied = clone $brief;
        $this->_em->persist($briefcopied);
        $this->_em->flush();
        return $this->json($briefcopied, Response::HTTP_CREATED);
    }



    /**
     * @Route(
     *      path="/api/formateurs/promo/{id_promo}/brief/{id_brief}",
     *      methods={"PUT"},
     *       defaults={
    *         "__controller"="\app\Controller\BriefController::updateBrief",
    *         "__api_resource_class"=Brief::class,
    *         "__api_collection_operation_name"="putBrief"
    *     }
     * )
     */
    public function updateBrief(
        Request $request,
        BriefRepository $briefRepository,
        PromoRepository $promosRepository,
        PromoBriefRepository $PromoBriefRepository,
        $id_promo,
        $id_brief
    ) {
        /*
        *cette methode modifie un brief
        */
        if (!$id_promo || !$id_brief) {

            return $this->json(["message" => "La demande n'a pas été trouvée"], Response::HTTP_NOT_FOUND);
        }
        //verifie si promo et brief existe
        $promo = $promosRepository->findOneById($id_promo);
        $brief = $briefRepository->findOneById($id_brief);

        if (!$promo || !$brief) {
            return $this->json(["message" => "La demande n'a pas été trouvée"], Response::HTTP_NOT_FOUND);
        }
        //retourne error le formateur connecter na pas cree les brief
        //$formateur = $this->get('security.token_storage')->getToken()->getUser();
        /*if ($brief->getFormateur() != $formateur) {
          return $this->json(["message" => "Vous n'avez pas le droit"], Response::HTTP_NOT_FOUND);
         }*/

        //verifie si le breif est dans le promo
        $resultbriefInPromo = $PromoBriefRepository->findBy(['brief' =>  $brief, 'promo' => $promo]);

        if (!$resultbriefInPromo) {
            return $this->json(["message" => "La demande n'a pas été trouvée"], Response::HTTP_NOT_FOUND);
        }

        if (!empty($request->getContent())) {
            $data = $request->getContent();
        } else {
            $data = $request->request->all();
        }
        $data = $this->_serializer->decode($data, "json");
        //verifie si la valeur existe on fait setter sinon on ne fait rien
        if (!empty($data['langue'])) {
            $brief->setLangue($data['langue']);
        }
        if (!empty($data['titre'])) {
            $brief->setTitre($data['titre']);
        }
        if (!empty($data['description'])) {
            $brief->setDescription($data['description']);
        }
        if (!empty($data['contexte'])) {
            $brief->setContexte($data['contexte']);
        }
        if (!empty($data['modalitePedagogique'])) {
            $brief->setModalitePedagogique($data['modalitePedagogique']);
        }
        if (!empty($data['critereDePerformance'])) {
            $brief->setCritereDePerformance($data['critereDePerformance']);
        }
        if (!empty($data['modaliteEvaluation'])) {
            $brief->setModaliteEvaluation($data['modaliteEvaluation']);
        }
        if (!empty($data['avatar'])) {
            $brief->setAvatar($data['avatar']);
        }
        if (!empty($data['archivage'])) {
            $brief->setArchivage($data['archivage']);
        }
        // modifier les collections
        if (!empty($data['livrableAttendu'])) {
            foreach ($data['livrableAttendu'] as  $value) {
            $brief->addLivrableAttendu($value);               
            }
        }
        if (!empty($data['PromoBrief'])) {
            foreach ($data['PromoBrief'] as  $value) {
            $brief->addPromoBrief($value);
            } 
        }
        if (!empty($data['tags'])) {
            foreach ($data['tags'] as  $value) {
            $brief->addTag($value);
            } 
        }
        if (!empty($data['ressources'])) {
            foreach ($data['ressources'] as  $value) {
            $brief->addRessource($value);
            } 
        }
        if (!empty($data['niveaux'])) {
            foreach ($data['niveaux'] as  $value) {
            $brief->getNiveaux($value);
            } 
        }

        //suppression
        if (!empty($data['supprimmerLivrableAttendus'])) {
            foreach ($data['supprimmerLivrableAttendus'] as  $value) {
            $brief->removeLivrableAttendu($value);
            } 
        }
        if (!empty($data['supprimmerPromoBrief'])) {
            foreach ($data['supprimmerPromoBrief'] as  $value) {
            $brief->removePromoBrief($value);
            } 
        }
        if (!empty($data['supprimmerTags'])) {
            foreach ($data['supprimmerTags'] as  $value) {
            $brief->removeTag($value);
            } 
        }
        if (!empty($data['supprimmerRessources'])) {
            foreach ($data['supprimmerRessources'] as  $value) {
            $brief->removeRessource($value);
            } 
        }
        if (!empty($data['supprimmerNiveaux'])) {
            foreach ($data['supprimmerNiveaux'] as  $value) {
            $brief->removeNiveau($value);
            } 
        }
        $this->_em->flush();
        return $this->json($brief, Response::HTTP_OK);
    }





    /**
     * @Route(
     *      path="/api/formateurs/promo/{id_promo}/brief/{id_brief}/assignation",
     *      methods={"PUT"}
     * )
     */
    public function assignBrief(
        $id_promo,
        $id_brief,
        ApprenantRepository $apprenantRepository,
        BriefRepository $briefRepository,
        PromoRepository $promosRepository,
        GroupeRepository $groupeRepository,
        PromoBriefRepository $briefMaPromoRepository,
        Request $request
    ) {
        if (!$id_promo || !$id_brief) {

            return $this->json(["message" => "La demande n'a pas été trouvée"], Response::HTTP_NOT_FOUND);
        }
        //verifie si promo et brief existe
        $brief = $briefRepository->find($id_brief);
        $promo = $promosRepository->find($id_promo);

        if (!$promo || !$brief) {
            return $this->json(["message" => "La demande n'a pas été trouvée"], Response::HTTP_NOT_FOUND);
        }
        //verifie si le breif est dans le promo
        $briefMaPromo = $briefMaPromoRepository->findOneBy(['brief' =>  $brief, 'promo' => $promo]);

        if (!$briefMaPromo) {
            return $this->json(["message" => "La demande n'a pas été trouvée"], Response::HTTP_NOT_FOUND);
        }
        if (!empty($request->getContent())) {
            $data = $request->getContent();
        } else {
            $data = $request->request->all();
        }
        $data = $this->_serializer->decode($data, "json");

        //affecter
        if (!empty($data['action']) && $data['action'] == "affecter") {
            //affecter le brief a un apprenant
            if (!empty($data['apprenant_id'])) {
                $briefApprenant = new PromoBriefApprenant();
                $apprenant = $apprenantRepository->findOneById($data['apprenant_id']);
                $status =  $data['statut'] ?? "livrer";
                $briefApprenant->setStatut($status);
                $briefApprenant->addApprenant($apprenant);
                $briefApprenant->addPromoBrief($briefMaPromo);
                $this->_em->persist($briefApprenant);
                $this->_em->flush();
            } elseif (!empty($data['groupes_id'])) {
                //affecter le brief a un groupes&groupes
                $briefPromo = new PromoBrief();
                $groupe = $groupeRepository->find($data['groupes_id']);
                $brief->addGroupe($groupe);

                //ajoute la promo de ce groupe dans le brief
                $promoGroupe = $groupe->getPromo();
                $nomGroupe = $groupe->getNom();
                if (!empty($promoGroupe) && $nomGroupe != 'Groupe Principal') {

                    $briefPromo = new PromoBrief();
                    $briefPromo->setBrief($brief);
                    $briefPromo->setPromo($promoGroupe);
                    $this->_em->persist($briefPromo);
                    $this->_em->flush();
                } else {
                    return $this->json(["message" => "La demande n'a pas été trouvée"], Response::HTTP_NOT_FOUND);
                }
                // if (gettype($data['groupes_id']) == "array") {} else {}
            } else {
                return $this->json(["message" => "affecter le brief a un apprenant ou groupes."], Response::HTTP_NOT_FOUND);
            }

            $this->_em->flush();
            return $this->json(["message" => "Affecter"], Response::HTTP_NOT_FOUND);
        } elseif (!empty($data['action']) && $data['action'] == "desaffecter") {
            //desaffecter le brief a un apprenant
            if (!empty($data['apprenant_id'])) {
                $apprenant = $apprenantRepository->find($data['apprenant_id']);
                $briefApprenantRem = $apprenant->getPromoBriefApprenants();
                $briefMaPromoDel = $briefMaPromoRepository->findOneBy(['promoBriefApprenant' => $briefApprenantRem, 'promo' => $promo]);
                $this->_em->remove($briefMaPromoDel);
                $this->_em->flush();
            } elseif (!empty($data['groupes_id'])) {
                //affecter le brief a un groupes&groupes
                $groupe = $groupeRepository->find($data['groupes_id']);
                $brief->removeGroupe($groupe);
                $this->_em->flush();
                // if (gettype($data['groupes_id']) == "array") {} else {}
            } else {
                return $this->json(["message" => "desaffacter le brief a un apprenant ou groupes."], Response::HTTP_NOT_FOUND);
            }
            $this->_em->flush();
            return $this->json(["message" => "Desaffecter"], Response::HTTP_NOT_FOUND);
        } else {
            return $this->json(["message" => "La demande n'a pas été trouvée"], Response::HTTP_NOT_FOUND);
        }
    }
}
