<?php

namespace App\Form\Type;

use App\Entity\Tag;
use App\Form\DataTransformer\TagsArrayToStringTransformer;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TagsInputType extends AbstractType
{
    private $transformer;

    public function __construct(TagsArrayToStringTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new CollectionToArrayTransformer())
            ->addModelTransformer($this->transformer, true);
    }

    public function getParent()
    {
        return TextType::class;
    }
}
