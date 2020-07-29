<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $repository)
    {
        $products = $repository->findAllGreatherThanPrice(600);
        dump($products);

        dump($repository->findOneGreatherThanPrice(600));
        dump($repository->findOneExpensive());
        dump($repository->findAllAsPDO(500));

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
