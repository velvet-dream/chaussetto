<?php

namespace App\Form;

use App\Entity\Staff;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class StaffFormType extends AbstractType
{

    public function __construct(
        private UserPasswordHasherInterface $uph,
        private Security $security)
    {

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod("POST");
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $staff = $event->getData();
            $form = $event->getForm();

            if (!$staff || null === $staff->getId()){
                $form->add('name', TextType::class,[
                    'label' => 'Nom'
                ])
                ->add('last_name', TextType::class,[
                    'label' => 'Prénom'
                ])
                ->add('email', TextType::class,[
                    'label' => 'Adresse e-mail'
                ])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe ne sont pas identitques',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmez votre mot de passe'],
                ])
                ->add('submit', SubmitType::class, [
                    'label' => 'Ajouter',
                ]);

            } else {
                $form->add('name', TextType::class,[
                    'label' => "Nom",
                    'attr' => ['value'=> $staff->getName()],
                ])
                ->add('lastName', TextType::class,[
                    'label' => "Prénom",
                    'attr' => ['value'=> $staff->getLastName()],
                ])
                ->add('email', TextType::class,[
                    'label' => "Adresse e-mail",
                    'attr' => ['value'=> $staff->getEmail()],
                ])
                ->add('plainPassword', PasswordType::class, [
                    'label' => 'Ancien mot de passe',
                    'hash_property_path' => 'password',
                    'mapped' => false,
                    'constraints' => [
                        new SecurityAssert\UserPassword(message: 'Ancien mot de passe invalide'),
                    ],
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
                    'label' => 'Mettre à jour',
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Staff::class,
        ]);
    }
}
