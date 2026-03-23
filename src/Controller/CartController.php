<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    public function __construct(private readonly ProductRepository $productRepository)
    {
        
    }



    #[Route('/cart', name: 'app_cart', methods:['GET'])]
    public function index(SessionInterface $session, Cart $cart): Response
    {   
           $data = $cart->getCart($session);
        return $this->render('cart/index.html.twig', [
            'items' => $data['cart'],
            'total'=>$data['total']
        ]);
    }

  #[Route("/cart/add/{id}/", name: "app_cart_new", methods: ['GET'])]
   
    public function addProductToCart(int $id, SessionInterface $session, Request $request): Response
    
    {
        $cart = $session->get('cart', []);
       
        if (!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id]=1;
        }        
        $session->set('cart',$cart);        
        return $this->redirect($request->headers->get('referer') ?? $this->generateUrl('app_homepage'));
        
    }


 
     #[Route("/cart/remove", name: "app_cart_remove", methods: ['GET'])]
    public function remove(SessionInterface $session): Response
    {        
        $session->set('cart', []);       
        return $this->redirectToRoute('app_cart');
    }
 

     #[Route("/cart/increase/{id}", name: "app_cart_increase")]
     public function increase(int $id, SessionInterface $session, Request $request): Response
{
    $cart = $session->get('cart', []);

    if (!empty($cart[$id])) {
        $cart[$id]++;
    }

    $session->set('cart', $cart);
    return $this->redirectToRoute('app_cart');
  
}

#[Route("/cart/decrease/{id}", name: "app_cart_decrease")]
public function decrease(int $id, SessionInterface $session, Request $request): Response
{
    $cart = $session->get('cart', []);

    if (!empty($cart[$id])) {
        if ($cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]); 
        }
    }

    $session->set('cart', $cart);
    return $this->redirectToRoute('app_cart');
    
}







}
