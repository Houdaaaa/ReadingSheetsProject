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
        $response->headers->set('Access-Control-Allow-Origin', '*');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Content-Type', 'application/text');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type',true);
            return $response;
        }

        $readingSheets = $this->getDoctrine()
                              ->getRepository(ReadingSheet::class)
                              ->findAll();

        $jsonContent = $serializer->serialize($readingSheets, 'json');

        $response->setContent($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode('200');

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
        $response->headers->set('Access-Control-Allow-Origin', '*');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Content-Type', 'application/text');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type',true);
            return $response;
        }

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
            }
            else
            {
                $response->setStatusCode('404');
            } 
        }
        else
        {
            $response->setStatusCode('404');
        }
        
        return $response;
    }

    

    /**
     * @Route("/api/addBook", name="api_addBook", methods={"POST", "OPTIONS"})
     */
    public function addBook(Request $request) 
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
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
        
        if (isset($content["Title"]) && isset($content["category"]) && isset($content["Author"]) && isset($content["PagesNumber"]) && isset($content["Editor"]) && isset($content["EditionDate"]) && isset($content["Collection"]) && isset($content["OriginalLanguage"]) && isset($content["MainCharacters"]) && isset($content["Summary"]) && isset($content["EnjoyedExtract"]) && isset($content["CriticalAnalysis"]))
        {
            $readingSheet = new ReadingSheet();
           
            $category = $this->getDoctrine()
                             ->getRepository(Category::class)
                             ->findOneBy([
                                 'title' => $content["category"]
                             ]);
      
            $readingSheet->setTitle($content["Title"]);
            $readingSheet->setCategory($category);
            $readingSheet->setAuthor($content["Author"]);
            $readingSheet->setPagesNumber($content["PagesNumber"]);
            $readingSheet->setEditor($content["Editor"]);
            $readingSheet->setEditionDate($content["EditionDate"]);
            $readingSheet->setCollection($content["Collection"]);
            $readingSheet->setOriginalLanguage($content["OriginalLanguage"]);
            $readingSheet->setMainCharacters($content["MainCharacters"]);
            $readingSheet->setSummary($content["Summary"]);
            $readingSheet->setEnjoyedExtract($content["EnjoyedExtract"]);
            $readingSheet->setCriticalAnalysis($content["CriticalAnalysis"]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($readingSheet);
            $em->flush();
         
            $response->setStatusCode('200');
        }
        else 
        {
            $response->setStatusCode('404');
        }        

        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($query));
        return $response;  
    }


    /**
     * @Route("/api/{id}/editBook", name="api_editbook", methods={"PUT","OPTIONS"})
     */
    public function editBook(Request $request, $id) 
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
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
  
        if ($id!= null && isset($content["Title"]) && isset($content["category"]) && isset($content["Author"]) && isset($content["PagesNumber"]) && isset($content["Editor"]) && isset($content["EditionDate"]) && isset($content["Collection"]) && isset($content["OriginalLanguage"]) && isset($content["MainCharacters"]) && isset($content["Summary"]) && isset($content["EnjoyedExtract"]) && isset($content["CriticalAnalysis"]))
        {
            $readingSheet = $this->getDoctrine()
                                 ->getRepository(ReadingSheet::class)
                                 ->find($id);;
            

            $category = $this->getDoctrine()
                            ->getRepository(Category::class)  //quand on donne que le titre et donc jamais l'id
                            ->findOneBy([
                                'title' => $content["category"]
                            ]); 
            
      
            $readingSheet->setTitle($content["Title"]);
            $readingSheet->setCategory($category);
            $readingSheet->setAuthor($content["Author"]);
            $readingSheet->setPagesNumber($content["PagesNumber"]);
            $readingSheet->setEditor($content["Editor"]);
            $readingSheet->setEditionDate($content["EditionDate"]);
            $readingSheet->setCollection($content["Collection"]);
            $readingSheet->setOriginalLanguage($content["OriginalLanguage"]);
            $readingSheet->setMainCharacters($content["MainCharacters"]);
            $readingSheet->setSummary($content["Summary"]);
            $readingSheet->setEnjoyedExtract($content["EnjoyedExtract"]);
            $readingSheet->setCriticalAnalysis($content["CriticalAnalysis"]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($readingSheet);
            $em->flush();

            $response->setStatusCode('200');
        }
        else 
        {
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
        $response->headers->set('Access-Control-Allow-Origin', '*');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Content-Type', 'application/text');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type',true);
            return $response;
        }

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
        }
        else
        {
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
        $response->headers->set('Access-Control-Allow-Origin', '*');
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

            $response->setStatusCode('200');
        }
        else
        {
            $response->setStatusCode('404');
        }

        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($query));

        return $response;
    }

}