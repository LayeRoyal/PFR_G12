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
    *     path="api/groupe_tags",
    *     methods={"POST"}
    * )
    */
    public function createGrpTag(TagRepository $repo, Request $req , SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $manager)
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
    *     path="api/groupe_tags/{id}",
    *     methods={"PUT"}
    * )
    */
    public function putGrpTag($id, GroupeTagRepository $grpRepo ,TagRepository $repo, Request $req , SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $manager)
    {
         $grpTag=$req->getContent();
        $tab_grpTag = $serializer->decode($grpTag,"json");

        $newGrpTag=$grpRepo->find($id);
        foreach ($tab_grpTag['tags'] as $key => $value) {
            $tag=$repo->findOneBy(['libelle'=>$value]);
            $newGrpTag->addTag($tag);
        }
        $manager->persist($newGrpTag);
        $manager->flush();
        return $this->json($newGrpTag,Response::HTTP_CREATED);

    }
}
