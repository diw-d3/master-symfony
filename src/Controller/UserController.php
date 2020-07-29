<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/test-user-entity-cascade-persist", name="user")
     */
    public function index()
    {
        $product = new Product();
        $product->setName('TEST-'.uniqid());
        $product->setSlug('test-1234JJRBJBZ');
        $product->setDescription('TESTTTTT');
        $product->setPrice(10000);

        $user = new User();
        $user->setUsername('toto');
        $user->addProduct($product)
            ->addProduct($this->getDoctrine()->getRepository(Product::class)->findAll()[0])
        ;

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('user/index.html.twig');
    }
}
