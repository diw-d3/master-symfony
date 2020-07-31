<?php

namespace App\Tests;

use App\Cart;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartTest extends TestCase
{
    private $cart;

    protected function setUp()
    {
        $sessionMock = new Session(new MockArraySessionStorage());

        $this->cart = new Cart($sessionMock);
    }

    public function testCanAddProduct()
    {
        $product = $this->createMock(Product::class);
        $product->method('getId')->willReturn(345);

        $this->cart->addProduct($product, 1);

        $this->assertEquals([
            345 => ['product' => $product, 'quantity' => 1]
        ], $this->cart->getProducts());
    }

    public function testCanRemoveProduct()
    {
        $product1 = $this->createMock(Product::class);
        $product1->method('getId')->willReturn(12);
        $product2 = $this->createMock(Product::class);
        $product2->method('getId')->willReturn(13);

        $this->cart->addProduct($product1, 1);
        $this->cart->addProduct($product2, 3);

        $this->cart->removeProduct($product2);

        $this->assertCount(1, $this->cart->getProducts());
    }

    public function testCanGetAndCountProducts()
    {
        $product1 = $this->createMock(Product::class);
        $product1->method('getId')->willReturn(12);
        $product2 = $this->createMock(Product::class);
        $product2->method('getId')->willReturn(13);

        $this->cart->addProduct($product1, 1);
        $this->cart->addProduct($product2, 1);

        $this->assertCount(2, $this->cart->getProducts());
    }
}
