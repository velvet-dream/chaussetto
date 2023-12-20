<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Customer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerFormType extends AbstractType
{
    function __construct(
        private UserPasswordHasherInterface $uph,
        private Security $security,
    )
    {
        // empty
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod("POST");
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $customer = $event->getData();
            $form = $event->getForm();
            // checks if the Customer object is "new"
            // If no data is passed to the form, the data is "null".
            // This should be considered a new "Customer"
            if (!$customer || null === $customer->getId()) {
                $form->add('name', TextType::class, [
                    'label' => "Nom",
                    ])
                    ->add('lastName', TextType::class,[
                        'label' => "Prénom",
                    ])
                    ->add('email', TextType::class,[
                        'label' => "Adresse e-mail",
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
                        'label' => 'S\'inscrire',
                    ]);
            } else {
                $form->add('name', TextType::class,[
                        'label' => "Nom",
                        'attr' => ['value'=> $customer->getName()],
                    ])
                    ->add('lastName', TextType::class,[
                        'label' => "Prénom",
                        'attr' => ['value'=> $customer->getLastName()],
                    ])
                    ->add('email', TextType::class,[
                        'label' => "Adresse e-mail",
                        'attr' => ['value'=> $customer->getEmail()],
                    ])
                    ->add('plainPassword', PasswordType::class, [
                        'label' => 'Ancien mot de passe',
                        'hash_property_path' => 'password',
                        'mapped' => false,
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

        /**
         * The PRE_SUBMIT event is dispatched at the beginning of the Form::submit() method.
         *
         * It can be used to:
         *  - Change data from the request, before submitting the data to the form.
         *  - Add or remove form fields, before submitting the data to the form.
         *
         * @Event("Symfony\Component\Form\Event\PreSubmitEvent")
         */
        // $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
        //     $form = $event->getForm();
        //     $plainPwd = $form->get("plainPassword")->getData();
        //     // $plainPwd = $form->getData();
        //     var_dump($plainPwd);
        //     die();
        //     // $user = $this->security->getUser();
        //     // if (!$this->uph->isPasswordValid($user, $plainPwd)) {
        //     //     throw new AccessDeniedHttpException();
        //     // }
        // });
        
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
