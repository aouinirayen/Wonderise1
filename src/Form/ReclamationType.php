<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\StatusEnum;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Objet', TextareaType::class, [
                'attr' => ['rows' => 3],] )
            ->add('Description', TextareaType::class, [
                'attr' => ['rows' => 5], 
            ])
            
            
            ->add('Date', null, [
                'widget' => 'single_text'
            ]) 
            ->add('status', ChoiceType::class, [
                'choices' => StatusEnum::cases(), 
                'choice_label' => fn (StatusEnum $status) => $status->name,
            ])

            ->add('user'); 
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
