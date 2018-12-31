<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\ReadingSheet;
use App\Repository\ReadingSheetRepository;
use App\Form\ReadingSheetType; 

use App\Entity\Category;
use App\Form\CategoryType; 
use App\Repository\CategoryRepository;

class BookController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render('book/home.html.twig', [
            'title' => 'Mes fiches de lecture',
        ]);
    }

    
    /**
     * @Route("/book", name="book")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(ReadingSheet::class);
        $readingSheets = $repo->findAll();
        
        $repo2 = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repo2->findAll();
       
        return $this->render('book/index.html.twig', [
            'title' => 'Index',
            'readingSheets' => $readingSheets,
            'categories' => $categories
        ]);
    }
    

    /**
     * @Route("/book/add", name="addBook")
     * @Route("/book/{id}/edit", name="editbook")
     */
    public function formBook(Request $request, $id = null) {
        
        $readingSheet = new ReadingSheet();
        
        if($id != null)
        {
            $repo= $this->getDoctrine()->getRepository(ReadingSheet::class);
            $readingSheet = $repo->findOneBy(['id' => $id]);
        }

        $form = $this->createForm(ReadingSheetType::class, $readingSheet );
                    
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() )
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($readingSheet);
            $manager->flush(); 

            return $this->redirectToRoute('book_show', array('id' => $readingSheet->getId()) );
        }


        return $this->render('book/form.html.twig', [
            'title' => 'Fiche de lecture',
            'formReadingSheet' => $form->createView(),
            'editMode' => $readingSheet->getID() !== null
        ]);
    }


    /**
     * @Route("/book/search/{category}", name="index_show")
     */
    public function index_show($category) {

        $repo = $this->getDoctrine()->getRepository(ReadingSheet::class);
        $readingSheets = $repo->findAll();

        $filteredReadingSheets = array();
        foreach ($readingSheets as $readingSheet) {
            if($readingSheet->getCategory()->getTitle() == $category)
            {
                array_push($filteredReadingSheets, $readingSheet);
            }
        }

        $repo2 = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repo2->findAll();

        return $this->render('book/index.html.twig', [
            'title' => 'Index',
            'readingSheets' => $filteredReadingSheets,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/book/{id}", name="book_show")
     */
    public function show($id) {
        $repo = $this->getDoctrine()->getRepository(ReadingSheet::class);
        $readingSheet = $repo->find($id);

        return $this->render('book/show.html.twig', [
            'readingSheet' => $readingSheet,
            'title' => $readingSheet->getTitle()
        ]);
    }
    

    /**
    * @Route("/book/{id}/remove", name="removebook")
    */    
    public function delete($id){
        try{
            $em = $this->getDoctrine()->getManager();
            $post = $em->getRepository(ReadingSheet::class)->find($id);
            $em->remove($post);
            $em->flush();
        
            $this->addFlash('message', 'Fiche de lecture supprimée');
            return $this->redirectToRoute('book');
        } catch (Exception $e) {
            $this->addFlash('message', "La fiche de lecture n'a pas pu être supprimée");
        }
    }

}
