<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
<<<<<<< HEAD
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
=======
use Symfony\Component\Form\Extension\Core\Type\DateType;

>>>>>>> 4f07741 (”Init”)
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
<<<<<<< HEAD
=======
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
>>>>>>> 4f07741 (”Init”)

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de l\'offre',
                'attr' => [
                    'placeholder' => 'Entrez le titre de l\'offre'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le titre ne peut pas être vide. Veuillez entrer un titre pour l\'offre.'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Décrivez votre offre en détail'
                ]
                ,'constraints' => [
                    new NotBlank([
                        'message' => 'Le description ne peut pas être vide. Veuillez entrer un description pour l\'offre.'
                    ])
                ]
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix par personne (€)',
                'attr' => [
                    'placeholder' => '0.00',
                    'min' => 0,
                    'step' => '0.01'
                ],'constraints' => [
                    new NotBlank([
                        'message' => 'Le prix ne peut pas être vide. Veuillez entrer un prix pour l\'offre.'
                    ])
                ]
            ])
            ->add('nombrePlaces', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' => [
                    'min' => 1
                ],'constraints' => [
                    new NotBlank([
                        'message' => 'Le nombrePlaces ne peut pas être vide. Veuillez entrer un nombrePlaces pour l\'offre.'
                    ])
                ]
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de début'
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de Fin'
            ])
            ->add('pays', TextType::class, [
                'required' => false,
                'label' => 'Pays de destination',
                'help' => 'Utilisé pour afficher la météo'
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image principale',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG ou PNG)',
<<<<<<< HEAD
                    ])
                ],
            ])
            ->add('additionalPhotos', FileType::class, [
                'label' => 'Photos additionnelles',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader des images valides (JPG ou PNG)',
=======
>>>>>>> 4f07741 (”Init”)
                    ])
                ],
            ])
            ->add('additionalPhotos', CollectionType::class, [
                'entry_type' => FileType::class, // Each entry in the collection is a FileType
                'entry_options' => [
                    'constraints' => [
                        new File([
                            'maxSize' => '5M',
                            'mimeTypes' => ['image/jpeg', 'image/png'],
                            'mimeTypesMessage' => 'Veuillez uploader des images valides (JPG ou PNG)',
                        ])
                    ],
                    'attr' => ['class' => 'form-control'],
                ],
                'allow_add' => true,
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
