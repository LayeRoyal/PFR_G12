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
    public function putTag(TagRepository $repo, GroupeTagRepository $reposit, $id, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $manager )
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


