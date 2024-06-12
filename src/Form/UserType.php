<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\Form\AddressType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('json_id', HiddenType::class)
        ->add('name', TextType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('username', TextType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('email', TextType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('phone', TextType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('website', TextType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('address', AddressType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
