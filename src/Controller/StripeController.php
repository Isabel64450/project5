<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    $session->set('cart', []);

    return $this->render('stripe/index.html.twig', [
        'status' => 'success'
    ]);
}

#[Route('/pay/cancel', name: 'app_stripe_cancel')]
public function cancel(): Response
{
    return $this->render('stripe/index.html.twig', [
        'status' => 'cancel'
    ]);
}

#[Route('/stripe/notify', name: 'app_stripe_notify')]
    public function stripeNotify(Request $request, 
                                OrderRepository $orderRepository,
                                EntityManagerInterface $entityManager): Response

    {
       
        Stripe::setApiKey($_SERVER['STRIPE_SECRET_KEY']);      
       
        $endpoint_secret = $_SERVER['STRIPE_WEBHOOK_SECRET'];       
        $payload = $request->getContent();        
        $sigHeader = $request->headers->get('Stripe-Signature');       
        $event = null;

        try {
           
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {            
            return new Response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
           
            return new Response('Invalid signature', 400);
        }                
        switch ($event->type) {
            case 'payment_intent.succeeded':  
              
                $paymentIntent = $event->data->object;
                
                // // Enregistrer les détails du paiement dans un fichier
                /* $fileName = 'stripe-detail-'.uniqid().'.txt'; */

                 $orderId = $paymentIntent->metadata->orderId;
                 $order = $orderRepository->find($orderId);
                 $cartPrice=$order->getTotalPrice();
                 $stripeTotalAmount = $paymentIntent->amount/100;
                 $order->setIsPaymentCompleted(1);
                 $entityManager->flush();
                /*if(!$order) {
                    return new Response('Commande non trouvée', 404);
                }

                $cartPrice = $order->getTotalPrice();*/
                

                if($cartPrice==$stripeTotalAmount){
                    $order->setIsPaymentCompleted(1);
                    $entityManager->flush();
                }  
                /* file_put_contents($fileName, $orderId); */
                break;
            case 'payment_method.attached':   // Événement de méthode de paiement attachée
                // Récupérer l'objet payment_method
                 $paymentMethod = $event->data->object;  
                break;
            default :
                // Ne rien faire pour les autres types d'événements
                break;
        }

        
        return new Response('Événement reçu avec succès', 200);
    }




}
