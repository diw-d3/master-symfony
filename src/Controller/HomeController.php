<?php

namespace App\Controller;

use App\Cart;
use App\Entity\Product;
use App\Mailer;
use App\Repository\ProductRepository;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public static function getSubscribedServices()
    {
        return array_merge([
            'my_mailer' => Mailer::class,
        ], parent::getSubscribedServices());
    }

    /**
     * @Route("/", name="home")
     */
    public function index(
        ProductRepository $repository,
        ContainerInterface $container,
        Mailer $mailer
    ) {
        // dump($mailer === $container->get('my_mailer'));
        dump($mailer === $this->container->get('my_mailer'));
        dump($mailer);

        $mailer->send('matthieumota@gmail.com');

        $products = $repository->findAllGreatherThanPrice(600);
        dump($products);

        dump($repository->findOneGreatherThanPrice(600));
        dump($repository->findOneExpensive());
        dump($repository->findAllAsPDO(500));

        return $this->render('home/index.html.twig', [
            'products' => $repository->findMoreExpensive(),
        ]);
    }

    /**
     * @Route("/cart", name="cart")
     */
    public function cart(Cart $cart)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)
            ->findAll()[0];

        $cart->addProduct($product, 2);
        $cart->removeProduct($product);

        $cart->addProduct($product, 3);

        dump($cart->getProducts()); // [...]
        dump($cart->count()); // 3
    }
}
