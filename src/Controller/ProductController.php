<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/create", name="product_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Génerer le slug
            $product->setSlug(
                $slugger->slug($product->getName())->lower()
            );

            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            // Redirection vers la liste des produits
            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/edit/{id}", name="product_edit")
     */
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete")
     */
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager)
    {
        $token = $request->get('token');

        if ($this->isCsrfTokenValid('delete-product-'.$product->getId(), $token)) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_list');
    }

    /**
     * @Route("/product/{page}", name="product_list", requirements={"page"="\d+"})
     */
    public function list($page = 1, ProductRepository $productRepository)
    {
        $products = $productRepository->findAllWithPagination($page);
        $maxPage = ceil(count($products) / 10);

        return $this->render('product/list.html.twig', [
            'products' => $products,
            'max_page' => $maxPage,
            'page' => $page,
        ]);
    }

    /**
     * @Route("/product/{slug}", name="product_show")
     */
    public function show(Product $product, ProductRepository $productRepository)
    {
        // $product = $productRepository->find($id);
        // $this->getDoctrine()->getRepository(Product::class)->find($id);

        // if (!$product) {
        //     throw $this->createNotFoundException();
        // }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
