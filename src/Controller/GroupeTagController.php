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
     * @Route(path="api/admin/grptags", methods={"post"})
     */
    public function addGroupeTag(TagRepository $repo, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $manager )
    {
        $tag=$request->getContent();
        $tab_grp_tag = $serializer->decode($tag,"json");

         $new_grp_tag= new GroupeTag();
           $new_grp_tag->setLibelle($tab_grp_tag["libelle"]);
               
                foreach ($tab_grp_tag["tags"] as $key => $value ) { 
                $tag= $repo->findOneBy(['libelle' => $value]);
                $new_grp_tag->addTag($tag);
                }
         
        $manager->persist($new_grp_tag);
        $manager->flush();
        return $this->json($new_grp_tag,Response::HTTP_CREATED);

    }



    /**
     * @Route(path="api/admin/grptags/{id}", methods={"put"})
     */
    public function putGroupeTag(TagRepository $repo, GroupeTagRepository $reposi, $id, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $manager )
    {
        $tag=$request->getContent();
        $tab_grp_tag = $serializer->decode($tag,"json");
        dd($tag);

         $new_grp_tag= $reposi->find($id);
         
               
                foreach ($tab_grp_tag["tags"] as $key => $value ) { 
                $tag= $repo->findOneBy(['libelle' => $value]);
                $new_grp_tag->addTag($tag);
                }
         
        $manager->persist($new_grp_tag);
        $manager->flush();
        return $this->json($new_grp_tag,Response::HTTP_CREATED);

    }


     /**
     * @Route(path="api/admin/grptags/{id}", methods={"put"})
     */
    public function putGroupeTag(TagRepository $repo, GroupeTagRepository $reposi, $id, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $manager )
    {
        $tag=$request->getContent();
        $tab_grp_tag = $serializer->decode($tag,"json");
        dd($tag);

         $new_grp_tag= $reposi->find($id);
         
               
                foreach ($tab_grp_tag["tags"] as $key => $value ) { 
                $tag= $repo->findOneBy(['libelle' => $value]);
                $new_grp_tag->removeTag($tag);
                }
         
        $manager->persist($new_grp_tag);
        $manager->flush();
        return $this->json($new_grp_tag,Response::HTTP_CREATED);

    }

    
}


