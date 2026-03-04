<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
       return $this->render('homepage/index.html.twig', [
           'products' => $productRepository->findAll()
    

        ]);
    }



   #[Route('/product/{id}/show ', name: 'app_home_product_show', methods: ['GET'])]
    public function showProduct(Product $product, ProductRepository $productRepository,CategoryRepository $categoryRepository): Response 
    
    {
        $lastProductsAdd = $productRepository->findBy([],['id'=>'DESC'],5);//on crée la variable a laquelle on donne le repo et lam ethode findBy, puis on donne une limit de 5 en affichage

        return $this->render('homepage/show.html.twig', [ 
            'product'=>$product,
            'products'=>$lastProductsAdd,
            'categories'=>$categoryRepository->findAll()
        ]);
    } 
}
