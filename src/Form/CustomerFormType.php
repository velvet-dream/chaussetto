<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Customer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class CustomerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod("POST");
        // Si l'user n'est pas défini, alors on veut l'inscrire.
        if ($options['user'] === NULL) {
            $builder
                ->add('name', options:[
                'label' => "Nom",
                ])
                ->add('lastName', options:[
                    'label' => "Prénom",
                ])
                ->add('email', options:[
                    'label' => "Adresse e-mail",
                ])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe ne sont pas identitques',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmez votre mot de passe'],
                ]);       
        } else {
            $builder
                ->add('name', options:[
                    'label' => "Nom",
                    'attr' => ['value'=> $options['user']->getName()],
                ])
                ->add('lastName', options:[
                    'label' => "Prénom",
                    'attr' => ['value'=> $options['user']->getLastName()],
                ])
                ->add('email', options:[
                    'label' => "Adresse e-mail",
                    'attr' => ['value'=> $options['user']->getEmail()],
                ])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe ne sont pas identitques',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Nouveau mot de passe'],
                    'second_options' => ['label' => 'Confirmez votre nouveau mot de passe'],
                ])
                ->add('submit', SubmitType::class, [
                    'label' => $options['label_submit'],
                ]);
        }
        $builder->add('submit', SubmitType::class, [
            'label' => $options['label_submit'],
        ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'user' => NULL,
            'label_submit' => 'S\'inscrire',
        ]);
    }
}
