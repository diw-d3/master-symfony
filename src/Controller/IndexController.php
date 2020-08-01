<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/home2", name="homepage")
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     */
    public function homepage(ProductRepository $productRepository)
    {
        $products = $productRepository->findAllGreaterThanPrice(700);
        $favoriteProduct = $productRepository->findOneGreaterThanPrice(800);

        dump($session->get('user'));

        return $this->render('index/homepage.html.twig', [
            'products' => $products,
            'favorite_product' => $favoriteProduct,
        ]);
    }

    /**
     * @Route("/session", name="session")
     */
    public function session(SessionInterface $session)
    {
        if ($session->get('user')) { // Si la session existe, on l'écrase
            // Pour supprimer une session
            $session->set('user', null);
            // unset($_SESSION['user']);
        } else {
            // J'ajoute une clé user dans la session
            $session->set('user', 'Matthieu');
            // $_SESSION['user'] = 'Matthieu';
        }

        return $this->redirectToRoute('home');
    }
}
