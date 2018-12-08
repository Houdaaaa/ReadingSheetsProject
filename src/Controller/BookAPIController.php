<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\ReadingSheet;
use App\Repository\ReadingSheetRepository;
use App\Form\ReadingSheetType; 

use App\Entity\Category;
use App\Form\CategoryType; 
use App\Repository\CategoryRepository;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BookAPIController extends AbstractController
{
    
    /**
     * @Route("/api/books", name="api_Books", methods={"GET"})
     */
    public function index(Request $request) 
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $response = new JsonResponse();

        $readingSheets = $this->getDoctrine()
                              ->getRepository(ReadingSheet::class)
                              ->findAll();

        $jsonContent = $serializer->serialize($readingSheets, 'json');

        $response->setContent($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode('200');
        $query['valid'] = true; 

        return $response;
    }


    /**
     * @Route("/api/book/{id}", name="api_book_show", methods={"GET"})
     */
    public function show(Request $request, $id) 
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $response = new JsonResponse();
        
        if($id != null)
        {
            $readingSheet = $this->getDoctrine()
                                ->getRepository(ReadingSheet::class)
                                ->find($id);
            
            if ($readingSheet != null)
            {
                $jsonContent = $serializer->serialize($readingSheet, 'json');

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
     * @Route("/api/addBook", name="api_addBook", methods={"POST", "OPTIONS"})
     */
    public function formBook(Request $request) 
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

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
        
        if (isset($content["Title"]) && isset($content["Category"]) && isset($content["Author"]) && isset($content["Pages number"]) && isset($content["Editor"]) && isset($content["Edition date"]) && isset($content["Collection"]) && isset($content["Original language"]) && isset($content["Main characters"]) && isset($content["Summary"]) && isset($content["Enjoyed extract"]) && isset($content["Critical analysis"]))
        {
            $readingSheet = new ReadingSheet();
           
            $category = $this->getDoctrine()
                             ->getRepository(Category::class)
                             ->findOneBy([
                                 'title' => $content["Category"]
                             ]);
      
            $readingSheet->setTitle($content["Title"]);
            $readingSheet->setCategory($category);
            $readingSheet->setAuthor($content["Author"]);
            $readingSheet->setPagesNumber($content["Pages number"]);
            $readingSheet->setEditor($content["Editor"]);
            $readingSheet->setEditionDate($content["Edition date"]);
            $readingSheet->setCollection($content["Collection"]);
            $readingSheet->setOriginalLanguage($content["Original language"]);
            $readingSheet->setMainCharacters($content["Main characters"]);
            $readingSheet->setSummary($content["Summary"]);
            $readingSheet->setEnjoyedExtract($content["Enjoyed extract"]);
            $readingSheet->setCriticalAnalysis($content["Critical analysis"]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($readingSheet);
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
     * @Route("/api/{id}/editBook", name="api_editbook", methods={"PUT","OPTIONS"})
     */
    public function formBookEdit(Request $request, $id) 
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $response = new Response();
        $query = array();

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response = new Response();
            $response->headers->set('Content-Type', 'application/text');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type',true);
            return $response;
        }

        $json = $request->getContent();  
        $content = json_decode($json, true); 

        if ($id!= null && isset($content["Title"]) && isset($content["Category"]) && isset($content["Author"]) && isset($content["Pages number"]) && isset($content["Editor"]) && isset($content["Edition date"]) && isset($content["Collection"]) && isset($content["Original language"]) && isset($content["Main characters"]) && isset($content["Summary"]) && isset($content["Enjoyed extract"]) && isset($content["Critical analysis"]))
        {
            $readingSheet = $this->getDoctrine()
                                 ->getRepository(ReadingSheet::class)
                                 ->find($id);;
            
            $category = $this->getDoctrine()
                             ->getRepository(Category::class)
                             ->findOneBy([
                                 'title' => $content["Category"]
                             ]);
      
            $readingSheet->setTitle($content["Title"]);
            $readingSheet->setCategory($category);
            $readingSheet->setAuthor($content["Author"]);
            $readingSheet->setPagesNumber($content["Pages number"]);
            $readingSheet->setEditor($content["Editor"]);
            $readingSheet->setEditionDate($content["Edition date"]);
            $readingSheet->setCollection($content["Collection"]);
            $readingSheet->setOriginalLanguage($content["Original language"]);
            $readingSheet->setMainCharacters($content["Main characters"]);
            $readingSheet->setSummary($content["Summary"]);
            $readingSheet->setEnjoyedExtract($content["Enjoyed extract"]);
            $readingSheet->setCriticalAnalysis($content["Critical analysis"]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($readingSheet);
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
     * @Route("/api/book/search/{category}", name="api_index_show", methods={"GET"})
     */
    public function index_show($category) 
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $response = new JsonResponse();

        $readingSheets = $this->getDoctrine()
                              ->getRepository(ReadingSheet::class)
                              ->findAll();

        if($category != null){
            $filteredReadingSheets = array();
            foreach ($readingSheets as $readingSheet) {
                if($readingSheet->getCategory()->getTitle() == $category)
                {
                    array_push($filteredReadingSheets, $readingSheet);
                }
            }

            $jsonContent = $serializer->serialize($filteredReadingSheets, 'json');

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

        return $response;
    }

    

    /**
    * @Route("/api/book/{id}/remove", name="api_removebook", methods={"DELETE","OPTIONS"})
    */    
    public function delete($id)
    {
        $response = new Response();
        $query = array();

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Content-Type', 'application/text');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type',true);

            return $response;
        }

        if ($id != null) {
            $em = $this->getdoctrine()->getManager();
            $readingSheet = $em->getRepository(ReadingSheet::class)->find($id);
            $em->remove($readingSheet);
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