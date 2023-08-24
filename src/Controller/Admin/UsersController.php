<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/users', name:'app_admin')]
class UsersController extends AbstractController
{
    #[Route('/', name:'_index')]
    public function index(): Response
    {
        return $this->render('admin/user/index.html.twig');
    }
}
