<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Form\ProdcutsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function add(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // * on cre le produit
        $product = new Products();

        // * on cre le formulaire
        $productForm = $this->createForm(ProdcutsFormType::class, $product);

        // * ontraite la requete
        $productForm->handleRequest($request);

        // if ($productForm->isSubmitted() && $productForm->isValid()) {
        if ($productForm->isSubmitted()) {
            // dd($productForm);

            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);
            $product->setPrice($product->getPrice() * 100);
            $manager->persist($product);
            $manager->flush();
            $this->addFlash('success', 'Produit ajouté avec succes');
            return $this->redirectToRoute('app_admin_products');
        }
        return $this->render('admin/products/add.html.twig', compact(
            'productForm'
        ));
        // return $this->render('admin/products/add.html.twig', [
        //         'productForm' => $productForm->createView()
        // ]);
        // return $this->renderForm('admin/products/add.html.twig', compact('productForm'));
    }
    #[Route('/edit/{id}', name:'_edit')]
    public function edit(Request $request, SluggerInterface $slugger, EntityManagerInterface $manager, Products $product): Response
    {
        // * on verfie si le user peut editer aved voter
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);



        // * on cre le formulaire
        $productForm = $this->createForm(ProdcutsFormType::class, $product);


        // * ontraite la requete
        $productForm->handleRequest($request);
        $product->setPrice($product->getPrice() / 100);
        // if ($productForm->isSubmitted() && $productForm->isValid()) {
        if ($productForm->isSubmitted()) {
            // dd($productForm);

            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);
            $product->setPrice($product->getPrice() * 100);
            $manager->persist($product);
            $manager->flush();
            $this->addFlash('success', 'Produit modifié avec succes');
            return $this->redirectToRoute('app_admin_products');
        }


        return $this->render('admin/products/edit.html.twig', compact('productForm'));
    }
    #[Route('/delete/{id}', name:'_delete')]
    public function delete(Products $product): Response
    {
        // * on verfie si le user peut editer aved voter
        $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);
        return $this->render('admin/products/index.html.twig');
    }
}
