<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label_attr' => [
                    'class' => 'toto',
                ],
            ])
            ->add('slug')
            ->add('description')
            ->add('category', null, [
                'expanded' => false,
                'choice_label' => function ($category) {
                    return $category->getName();
                },
            ])
            ->add('tags', null, [
                'expanded' => true,
            ])
            ->add('price', MoneyType::class, [
                'divisor' => 100,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
