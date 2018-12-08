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
            'title' => 'Ma bibliothèque',
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
            $manager->persist($readingSheet); // manager permet d'insérer, modifier ou supprimer des lignes dans notre bdd
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

    

    //Précédents 
    //suppression des catégories + suppresion des fiches de lecture  ---> refactoring
    // a voir : service container de symfony

    //Français/anglais
    //Aligner les articles
    //décoration 
    //Boutons qui ne bougent pas

    //Si suppression d'une catgéorie tous ses livres se suppriment (pas sûr)
    //Ajouter image à chaque livre
    //Validation : message d'erreur anglais/français?

    //APIREST
    //Refactoring + enlever trucs non nécessaires (query ['valid'])

    //Relation parent-enfant angular très important à respecter
    //exam : gerer tout le fnctionnement (faire diagramme pour mieux expliquer
    //Communication entre composants 
    //ce composant appelle doctrine qui appelle ci qui appelle ça qui utilise l'API etc
    //question: si je veux ajouter ça dans la fenêtre comment je dois faire

    //Assert dans entité  : API non violables
}
