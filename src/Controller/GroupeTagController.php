<?php

namespace App\Controller;

use App\Entity\GroupeTag;
use App\Repository\TagRepository;
use App\Repository\GroupeTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeTagController extends AbstractController
{
     /**
    * @Route(
    *     path="api/admin/grptags,
    *     methods={"POST"}
    * )
    */
    public function createGrpTag(TagRepository $repo, Request $req , SerializerInterface $serializer, EntityManagerInterface $manager)
    {
         $grpTag=$req->getContent();
        $tab_grpTag = $serializer->decode($grpTag,"json");

        $newGrpTag= new GroupeTag();
        $newGrpTag->setLibelle($tab_grpTag['libelle']);
        foreach ($tab_grpTag['tags'] as $key => $value) {
            $tag=$repo->findOneBy(['libelle'=>$value]);
            $newGrpTag->addTag($tag);
        }
        $manager->persist($newGrpTag);
        $manager->flush();
        return $this->json($newGrpTag,Response::HTTP_CREATED);

    }

     /**
    * @Route(
    *     path="api/admin/grptags/{id}/tags,
    *     methods={"GET"},
    *     defaults={
    *         "__controller"="\app\Controller\GroupeTagController::putTag",
    *         "__api_resource_class"=GroupeTag::class,
    *         "__api_collection_operation_name"="getTags"
    *     }
    * )
    */

    public function putTag(TagRepository $repo, GroupeTagRepository $reposit, $id, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager )
    {
        $grpe_tag=$reposit->find($id);
        if(!$grpe_tag)
        {
            return $this ->json(["message" => "Groupe tag not found"], Response::HTTP_NOT_FOUND);
        }
       //supprimer ts les Tags pour ajouter ce qu'on veut
        $tags=$grpe_tag->getTags();
         foreach ($tags as $value) {
            $grpe_tag->removeTag($value);
         }
       
       $tags=$request->getContent();
       $tab_tags = $serializer->decode($tags,"json");

       foreach ($tab_tags['tags'] as $value) {
        $tag=$repo->findOneBy(['libelle'=>$value]);
        $grpe_tag->addTag($tag);
    }
     $manager->persist($grpe_tag);
    $manager->flush();
    return $this->json($grpe_tag,Response::HTTP_OK);

    }
}
