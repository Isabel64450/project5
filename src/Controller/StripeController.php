<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }
      #[Route('/pay/success', name: 'app_stripe_success')]
    public function success(SessionInterface $session): Response
    {
        $session->set('cart',[]);
        // Rendre la vue "index.html.twig" avec le nom du contrôleur
        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }

#[Route('/pay/cancel', name: 'app_stripe_cancel')]
    public function cancel(): Response
    {
        // Rendre la vue "index.html.twig" avec le nom du contrôleur
        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }






}
