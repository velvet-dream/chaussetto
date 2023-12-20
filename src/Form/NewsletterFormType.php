<?php

namespace App\Form;

use App\Entity\NewsletterSubscribers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NewsletterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options=['method' => 'POST']): void
    {
        $builder
            ->setMethod('POST')
            ->add('adress')
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NewsletterSubscribers::class,
        ]);
    }
}
