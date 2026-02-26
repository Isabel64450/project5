<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/{id}/make-editor', name: 'app_user_make_editor', requirements: ['id' => '\d+'])]
    public function makeEditor(User $user, EntityManagerInterface $entityManager): Response
    {
        $roles = $user->getRoles();
        if (!in_array('ROLE_EDITOR', $roles)) {
            $roles[] = 'ROLE_EDITOR';
            $user->setRoles($roles);
        }

        $entityManager->flush();

        $this->addFlash('success', sprintf('Le rôle éditeur a été ajouté à %s !', $user->getUserIdentifier()));

        return $this->redirectToRoute('app_user_list');
    }

    #[Route('/users', name: 'app_user_list')]
    public function listUsers(EntityManagerInterface $entityManager): Response
    {
    
    $users = $entityManager->getRepository(User::class)->findAll();

   
    return $this->render('user/list.html.twig', [
        'users' => $users,
    ]);
}





}