<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Category;
use App\Form\CategoryType; 
use App\Repository\CategoryRepository;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class CategoryAPIController extends AbstractController
{

    /**
     * @Route("/api/book/category/config", name="api_categoryConfig", methods={"GET"})
    */
    public function category_index(Request $request)
    {   
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $response = new JsonResponse();

        $categories = $this->getDoctrine()
                           ->getRepository(Category::class)
                           ->findAll();

        $jsonContent = $serializer->serialize($categories, 'json');

        $response->setContent($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode('200');
        $query['valid'] = true; 

        return $response;
    }

    /**
     * @Route("/api/book/category/config/{title}", name="api_category_show", methods={"GET"})
    */
    public function category_show(Request $request, $title) 
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $response = new JsonResponse();

        if($title != null)
        {
            $category = $this->getDoctrine()
                              ->getRepository(Category::class)
                              ->findOneBy(
                                  ['title' => $title]
                              );

            if($category != null)
            {
                $jsonContent = $serializer->serialize($category, 'json');

                $response->setContent($jsonContent);
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode('200');
                $query['valid'] = true; 
            }
            else
            {
                $query['valid'] = false; 
                $response->setStatusCode('404');
            }      
        }
        else
        {
            $query['valid'] = false; 
            $response->setStatusCode('404');
        }

        return $response;
        
    }

    /**
     * @Route("/api/book/category/addCategory", name="api_addCategory", methods={"POST", "OPTIONS"})
    */
    public function category_add(Request $request) 
    {
        $response = new Response();
        $query = array();

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Content-Type', 'application/text');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type',true);
            return $response;
        }

        $json = $request->getContent();
        $content = json_decode($json, true); 
        
        if (isset($content["title"]) && isset($content["description"]))
        {
            $cat = new Category();

            $cat->setTitle($content["title"]);
            $cat->setDescription($content["description"]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($cat);
            $em->flush();
            
            $query['valid'] = true;             //non nécessaire
            $response->setStatusCode('200');
        }
        else 
        {
            $query['valid'] = false; 
            $response->setStatusCode('404');
        }        

        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($query));
        return $response;
    }

    /**
     * @Route("/api/book/category/{id}/editCategory", name="api_editCategory", methods={"PUT", "OPTIONS"})
    */
    public function category_edit(Request $request, $id) 
    {
        $response = new Response();
        $query = array();

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Content-Type', 'application/text');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type',true);

            return $response;
        }

        $json = $request->getContent();
        $content = json_decode($json, true);

        if ($id!= null && isset($content["title"]) && isset($content["description"]))
        {
            $category = $this->getDoctrine()
                     ->getRepository(Category::class)
                     ->find($id);

            $category->setTitle($content["title"]);
            $category->setDescription($content["description"]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $query['valid'] = true;                 
            $response->setStatusCode('200');
        }
        else 
        {
            $query['valid'] = false; 
            $response->setStatusCode('404');
        }        

        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($query));

        return $response;
    }
    

    /**
    * @Route("/api/book/category/{id}/removeCategory", name="api_removeCategory", methods={"DELETE", "OPTIONS"})
    */    
    public function category_delete($id)
    {
        $response = new Response();
        $query = array();

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Content-Type', 'application/text');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type',true);

            return $response;
        }

        if ($id != null) {
            $em = $this->getdoctrine()->getManager();
            $category = $em->getRepository(Category::class)->find($id);
            $em->remove($category);
            $em->flush();

            $query['valid'] = true; 
            $response->setStatusCode('200');
        }
        else
        {
            $query['valid'] = false; 
            $response->setStatusCode('404');
        }

        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($query));

        return $response;
    }
}