<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/category/new', name: 'app_category_new')]
    public function addCategory(EntityManagerInterface $entityManager, Request $request): Response
    {   
        $category = new Category();
        $form = $this ->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $this->render('category/newCategory.html.twig', [
            'formCategory' => $form->createView(),
        ]);
    }


    #[Route('/category/{id}/edit', name: 'app_category_edit', requirements: ['id' => '\d+'])]
    public function editCategory(
    Category $category,
    Request $request,
    EntityManagerInterface $entityManager): Response 
    {
    
       $form = $this->createForm(CategoryFormType::class, $category);
       $form->handleRequest($request);    
       if ($form->isSubmitted() && $form->isValid()) {        
        $entityManager->flush();
        return $this->redirectToRoute('app_category_new'); 
    }

    return $this->render('category/editCategory.html.twig', [
        'formCategory' => $form->createView(),
        'category' => $category
    ]);

    }






}
