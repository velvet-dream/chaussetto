<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Promotion;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionFormType extends AbstractType
{
    public function __construct(
        private ProductRepository $prodRepo,
    )
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->add('label', TextType::class,[
                'label' => 'Intitulé',
                'attr' => ['value'=> $options['promotion']->getLabel()],
            ])
            ->add('rate', NumberType::class ,[
                'label' => 'Taux de réduction (%)',
                'attr' => ['value'=> $options['promotion']->getRate()],
            ]);
        if ($choices = $this->prodRepo->findAvailableProductsForPromotion()) {  
            $builder->add('products', EntityType::class, [
                'class' => Product::class,
                'choices' => $choices,
                'multiple' => true,
                'label' => 'Produits à mettre en promotion',
                'expanded' => true,
            ]);
        }
        $builder->add('submit', SubmitType::class, [
            'label' => $options['label'],
        ]);


        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
        //     $promotion = $event->getData();
        //     $form = $event->getForm();
        //     // Si "promotion" existe bien, on est en train de l'update
        //     if ($promotion && null !== $promotion->getId()) {
                
        //         $form
        //             ->add('label', TextType::class,[
        //                 'label' => 'Intitulé',
        //                 'attr' => ['value'=> $promotion->getLabel()],
        //             ])
        //             ->add('rate', NumberType::class ,[
        //                 'label' => 'Taux de réduction',
        //                 'attr' => ['value'=> $promotion->getRate()],
        //             ]);
        //         if ($choices = $this->prodRepo->findAvailableProductsForPromotion()) {
                    
        //             $form->add('products', EntityType::class, [
        //                 'class' => Product::class,
        //                 'choices' => $choices,
        //                 'multiple' => true,
        //                 // 'expanded' => true,
        //             ]);
        //         }
                
        //     } else {
        //         $form->add('label', TextType::class,[
        //                 'label' => 'Intitulé',
        //             ])
        //             ->add('rate', NumberType::class ,[
        //                 'label' => 'Taux de réduction',
        //             ]);
        //     }
        // });    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
            'promotion' => new Promotion(),
            'label' => 'Créer'
        ]);
    }
}
