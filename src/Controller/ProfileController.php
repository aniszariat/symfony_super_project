<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile', name: 'app_profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileAnisController',
        ]);
    }
    #[Route('/slug', name: '_slug')]
    public function slug(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'SlugController',
        ]);
    }
}
