<?php

namespace App\Controller;

use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'app_categories')]
class CategoriesController extends AbstractController
{
    #[Route('/{slug}', name: '_list')]
    public function list(Categories $category): Response
    {
        // get all products of a ctegory
        $products = $category->getProducts();
        return $this->render(
            'categories/list.html.twig',
            compact(
                'category',
                'products'
            )
        );
        // return $this->render('categories/list.html.twig', [
        //     'category' => $category,
        //     'products' => $products
        // ]);
    }
}
