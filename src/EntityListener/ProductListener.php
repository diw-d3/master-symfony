<?php

namespace App\EntityListener;

use App\Entity\Product;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Product $product, LifecycleEventArgs $event)
    {
        $product->setSlug(
            $this->slugger->slug($product->getName())->lower().'-listener'
        );
    }
}
