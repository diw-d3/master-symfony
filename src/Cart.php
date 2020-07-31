<?php

namespace App;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function addProduct(Product $product, int $quantity)
    {
        $products = $this->getProducts();

        $products[$product->getId()] = [
            'product' => $product, 'quantity' => $quantity,
        ];

        $this->session->set('products', $products);
    }

    public function removeProduct(Product $product)
    {
        $products = $this->getProducts();

        if (array_key_exists($product->getId(), $products)) {
            unset($products[$product->getId()]);
        }

        $this->session->set('products', $products);
    }

    public function getProducts()
    {
        return $this->session->get('products', []);
    }

    public function count()
    {
        return count($this->getProducts());
    }
}
