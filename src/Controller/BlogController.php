<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{

    /**
     * @Route("/blog", name="blog")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BlogController.php',
        ]);
    } 
    
/**
 * @Route("/post/{page}" , name="blog_list" , defaults={"page":5}, requirements={"id"="\d+"})
 */
   
    public function listBlog($page = 1, Request $request){
       $limit = $request->get('limit',10);
       $repository = $this->getDoctrine()->getRepository(BlogPost::class);
       $items = $repository->findAll();
       return $this->json(
          [
              'page' => $page,
              'limit' => $limit,
              'data' => array_map(function(BlogPost $item){
                  return $this->generateUrl('blog_by_slug', ['slug'=>$item->getSlug()]);
              },$items)
          ]
        );
    }

    /**
     *@Route("/post/{id}", name="blog_by_id" , requirements={"id"="\d+"})
     */
    public function post($id){
        return $this->json(
            $this->getDoctrine()->getRepository(BlogPost::class)->find($id)
        );
    }

     /**
     *@Route("/post/{slug}", name="blog_by_slug")
     */
    public function postBySlug($slug){
        return $this->json(
            self::POSTS[array_search($slug, array_column(self::POSTS, 'slug'))]
            );
    }

    /**
     *@Route("/addpost", name="blog_add" , methods={"POST"})
     */
    public function add(Request $request){
        /**
         * @var Serializer $serializer
         */
        $serializer = $this->get('serializer');
        
        $blogPost= $serializer->deserialize($request->getContent(), BlogPost::class , 'json');
        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();
        return $this->json($blogPost);

    }
}
