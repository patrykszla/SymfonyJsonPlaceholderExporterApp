<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PostType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options): void 
    {
        $builder
        ->add('title', TextType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('body', TextType::class, [
            'attr' => ['class' => 'form-control']
        ]);
    }
}