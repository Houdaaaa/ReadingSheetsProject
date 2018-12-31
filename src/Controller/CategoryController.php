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


class CategoryController extends AbstractController
{

    /**
     * @Route("/book/category/config", name="categoryConfig")
    */
    public function config()
    {   
        $repo2 = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repo2->findAll();
       
        return $this->render('book/categoryConfig.html.twig', [
            'title' => 'Index',
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/book/category/config/{title}", name="category_show")
     */
    public function category_show($title) {
        
        $repo = $this->getDoctrine()->getRepository(Category::class);
        $category = $repo->findBy(
            ['title' => $title]
        )[0];

        return $this->render('book/categoryShow.html.twig', [
            'category' => $category,
            'title' => $category->getTitle()
        ]);
    }

    /**
     * @Route("/book/category/addCategory", name="addCategory")
     * @Route("/book/category/{id}/editCategory", name="editCategory")
     */
    public function formCategory(Request $request, $id = null) {
        
        $category = new Category();

        if($id != null)
        {
            $repo= $this->getDoctrine()->getRepository(Category::class);
            $readingSheet = $repo->findOneBy(['id' => $id]);
        }

        $form = $this->createForm(CategoryType::class, $category );
                    
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() )
        {

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category); 
            $manager->flush();
            

            return $this->redirectToRoute('category_show', array('title' => $category->getTitle()));
        }


        return $this->render('book/formCategory.html.twig', [
            'title' => 'Catégories',
            'formCategory' => $form->createView(),
            'editMode' => $category->getID() !== null
        ]);
    }


    /**
    * @Route("/book/category/{id}/removeCategory", name="removeCategory")
    */    
    public function deleteCategory($id){
        try{
            $em = $this->getDoctrine()->getManager();
            $post = $em->getRepository(Category::class)->find($id);
            $em->remove($post);
            $em->flush();
        
            $this->addFlash('message', 'Catégorie supprimée');
            return $this->redirectToRoute('categoryConfig');
        } catch (Exception $e) {
            $this->addFlash('message', "Cette catégorie n'a pas pu être supprimée");
        }
    }

}
