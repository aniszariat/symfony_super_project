<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/products', name:'app_admin')]
class ProductsController extends AbstractController
{
    #[Route('/', name:'_products')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PRODUCT_ADMIN');
        return $this->render('admin/products/index.html.twig');
    }
    #[Route('/add', name:'_add')]
    public function add(): Response
    {
        return $this->render('admin/products/index.html.twig');
    }
    #[Route('/edit/{id}', name:'_edit')]
    public function edit(Products $product): Response
    {
        // * on verfie si le user peut editer aved voter
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);
        return $this->render('admin/products/index.html.twig');
    }
    #[Route('/delete/{id}', name:'_delete')]
    public function delete(Products $product): Response
    {
        // * on verfie si le user peut editer aved voter
        $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);
        return $this->render('admin/products/index.html.twig');
    }
}
