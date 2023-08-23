<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'app_categories')]
class CategoriesController extends AbstractController
{
    #[Route('/{slug}', name: '_list')]
    public function list(Categories $category, ProductsRepository $productsRepository, Request $request): Response
    {
        // * On cherhce le n° de la page
        $page = $request->query->getInt('page', 1);

        // * On va chercher la liste des produits de la catégorie
        $products = $productsRepository->findProductsPaginated($page, $category->getSlug(), 3);
        // dd($products);
        return $this->render(
            'categories/list.html.twig',
            compact(
                'category',
                'products'
            )
        );
    }

    /**
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
    **/

}
