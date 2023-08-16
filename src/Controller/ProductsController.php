<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products', name:'app_products')]
class ProductsController extends AbstractController
{
    #[Route('/', name:'_index')]
    public function index(): Response
    {
        return $this->render('products/index.html.twig', [
            'controller_name'=>'ProductsController'
        ]);
    }

    #[Route('/{slug}', name:'_slug')]
    public function details(Products $product): Response
    {
        // dd($product);
        return $this->render('products/details.html.twig', compact('product'));
    }
}
