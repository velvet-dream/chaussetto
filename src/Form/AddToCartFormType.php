<?php

namespace App\Form;

use App\Entity\Cart;
use App\Entity\CartLine;
use App\Entity\Product;
use App\Repository\CartRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddToCartFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->add('quantity')
            // ->add('cart', EntityType::class, [
            //     'class' => Cart::class,
            //     'choice_label' => 'id',
            // ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'id',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter au panier'
            ])
        ;
                
        // $builder->addEventListener(FormEvents::PRE_SUBMIT, function (
        //     FormEvent $e, 
        //     Security $security=new Security(),
        //     CartRepository $cartRepo,
        //     ): void {
        //         $customer = $security->getUser();
        //         $cartLine = $e->getData();
        //         if (($currentCart = $cartRepo->getLastCart($customer)) === NULL) {
        //             $currentCart = new Cart();
        //         }
        //         $cartLine->setCart($currentCart);
        //         $e->setData($cartLine);
        // });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CartLine::class,
        ]);
    }
}
