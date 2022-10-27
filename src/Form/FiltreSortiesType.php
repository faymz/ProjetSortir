<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\FiltreSorties;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreSortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campusFiltre', EntityType::class, [
                'class' => Campus::class,
                'label' => false ,
                'required' => false,
                'placeholder' => '--- Sélectionner ---',
                'choice_label' => 'nom',
                'query_builder' => function (CampusRepository $campusRepository){
                    return $campusRepository->createQueryBuilder('c')->orderBy('c.nom', 'ASC');
                }
            ])
            ->add('motCle', TextType::class, [
                'label' => false ,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('dateDebutRech',DateType::class, [
                'label' => false,
                'required' => false,
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('dateFinRech', DateType::class, [
                'label' => false,
                'required' => false,
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('organisateurSortie', CheckboxType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['id' => 'organisateurFiltre']
            ])
            ->add('inscrit', CheckboxType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['id' => 'inscritFiltre']
            ])
            ->add('nonInscrit', CheckboxType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['id' => 'nonInscritFiltre']
            ])
            ->add('etatFiltre', CheckboxType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['id' => 'statusFiltre']
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FiltreSorties::class,
            'method' => 'POST',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}