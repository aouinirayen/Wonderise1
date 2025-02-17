<?php

namespace App\Form;

use App\Entity\Experience;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre' , TextType::class, [
                'required' => false,
                'label' => 'Title',
                'attr' => ['class' => 'form-control']
            ] )
            ->add('description', TextType::class, [
                'required' => false,
                'label' => 'Description',
                'attr' => ['class' => 'form-control']
            ])
            ->add('url' , TextType::class, [
                'required' => false,
                'label' => 'Image URL',
                'attr' => ['class' => 'form-control']
            ])
            ->add('lieu', TextType::class, [
                'required' => false,
                'label' => 'Place',
                'attr' => ['class' => 'form-control']
            ])
            ->add('categorie',  TextType::class, [
                'required' => false,
                'label' => 'Category',
                'attr' => ['class' => 'form-control']
            ])
            ->add('id_client',  TextType::class, [
                'required' => false,
                'label' => 'Id ',
                'attr' => ['class' => 'form-control']
            ])
            ->add('rating', TextType::class, [
                'required' => false,
                'label' => 'Description',
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
        ]);
    }
}
