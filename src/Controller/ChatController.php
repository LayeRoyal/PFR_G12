<?php

namespace App\Controller;


use App\Entity\Chat;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use App\Repository\PromoRepository;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FilDeDiscussionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
     /**
     * @Route(
     *      path="api/users/promo/{id}/apprenant/{num}/chats",
     *      methods={"GET"},
     *      defaults={
     *          "__controller"="App\Controller\ChatController::getChats",
     *          "__api_resource_class"=Chat::class,
     *          "__api_collection_operation_name"="chats"
     * }
     * )
     */
    public function getChats(ChatRepository $chatRepository)
    {
        
        $chat = $chatRepository->findByCreateAt();
   //     dd($chat);
        return $this->json($chat, Response::HTTP_OK);
    }


      /**
     * @Route(
     *      path="api/users/promo/{id}/apprenant/{num}/chats",
     *      methods={"POST"},
     *      defaults={
     *          "__controller"="App\Controller\ChatController::addchats",
     *          "__api_resource_class"=Chat::class,
     *          "__api_collection_operation_name"="chat"
     * }
     * )
     */
    public function addchats(Request $request,SerializerInterface $serializer,
                             ValidatorInterface $validator,EntityManagerInterface $manager,
                             PromoRepository $promoRepository,ApprenantRepository $apprenantRepository,
                             UserRepository $userRepository,$id,FilDeDiscussionRepository $filDiscussion)
        {
        $data = $request->request->all();
         
        //$data = json_decode($request->getContent(),true);
        $pieceJoint = $request->files->get("pieceJointes");
        //dd($pieceJoint);
        //dd($data);
        $chat = new Chat;
        $chat->setMessage($data['message']);
        $chat->setCreateAt(new \DateTime());
        $promo  = $promoRepository->findById($id);
        if($promo){
            $chat->setPromo($promo[0]);
        }
        else{
            return $this->json("le tag avec id $id n'existe pas", Response::HTTP_BAD_REQUEST);
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $chat->setUser($user);
        if($pieceJoint)
        $pieceJoint = fopen($pieceJoint->getRealPath(), "rb");
        $data["pieceJointes"] = $pieceJoint;
        $chat->setPieceJointes($data["pieceJointes"]);
            // dd($chat);
        $errors = $validator->validate($chat);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, "json");
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, [], true);
        }
        $manager->persist($chat);
        $manager->flush();

        return $this->json($chat, Response::HTTP_CREATED);
    }
    // public function addchats(Request $request,SerializerInterface $serializer,
    //                         ValidatorInterface $validator,EntityManagerInterface $manager,
    //                         PromoRepository $promoRepository,ApprenantRepository $apprenantRepository,
    //                         UserRepository $userRepository,$id,FilDeDiscussionRepository $filDiscussion, $num) 
    //         {
    
    //         //$data = $request->request->all();
    //         $data = json_decode($request->getContent(),true);
    //         //$pieceJoint = $request->files->get("pieceJointes");
    //         //dd($pieceJoint);
    //         //dd($data);
    //         $promo  = $promoRepository->findById($id);
    //             if($promo){
    //                 $chat->setPromo($promo[0]);
    //             }
    //             else{
    //                 return $this->json("le tag avec id $id n'existe pas", Response::HTTP_BAD_REQUEST);
    //             }
    //         $chat = $serializer->denormalize($data, Chat::Class);
    //         $chat->setCreatAt(new \DateTime());

    //         $user = $this->get('security.token_storage')->getToken()->getUser();
    //         $chat -> setUser($user);
            
    //         // if( $user ){
    //         //     $chat->setUser($user[0]);
    //         // }
    //         // else{
    //         //     return $this->json("le user avec id $id n'existe pas", Response::HTTP_BAD_REQUEST);
    //         // }
    //         // if($pieceJoint)
    //         // $data["pieceJointes"] = $pieceJoint;
    //         // $chat->setPieceJointes($data["pieceJointes"]);
    //         // dd($chat);
    //         $errors = $validator->validate($chat);
    //         if (count($errors)) {
    //             $errors = $serializer->serialize($errors, "json");
    //             return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, [], true);
    //         }
    //         $manager->persist($chat);
    //         $manager->flush();
    //         return $this->json(($chat), Response::HTTP_CREATED);
    // }

}
