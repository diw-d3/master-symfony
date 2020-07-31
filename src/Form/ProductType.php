<?php

namespace App\Form;

use App\Entity\Product;
use App\Form\Type\TagsInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ProductType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

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
            ->add('tags', TagsInputType::class)
            ->add('price', MoneyType::class, [
                'divisor' => 100,
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();

            // Récupérer l'utilisateur et ajouter le champ
            if ($this->security->isGranted('ROLE_ADMIN')) {
                $form->add('admin');
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            if (isset($data['admin']) && '9' === $data['admin']) {
                $data['name'] = 'Toto';
                $data['description'] = 'Ok';
                $data['price'] = 95;
            }

            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
