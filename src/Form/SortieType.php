<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', textType::class, [
                'label' => 'Nom de sortie : '
            ])
            ->add('dateHeureDebut', datetimeType::class,[
                'label' =>'Date et heure de la sortie : ',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('dateLimiteInscription', dateType::class, [
                'label' => 'Date limite d\'inscription : ',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('nbInscriptionsMax', numberType::class, [
                'label' => 'Nombre de places :'
            ])
            ->add('duree', timeType::class, [
                'label' => 'Durée',
                'html5' => true,
                'widget' => 'single_text'
            ] )
            ->add('infosSortie', textType::class, [
                'label' => 'Description et infos'
            ])
            ->add('ville', EntityType::class,[
                'class' => Ville::class,
                'label' => "Ville :",
                'placeholder' => '--- Sélectionner ---',
                'choice_label' => 'nom',
                'query_builder' => function (VilleRepository $villeRepository){
                    return $villeRepository->createQueryBuilder('v')->orderBy('v.nom', 'ASC');
                },

                'constraints' => new NotBlank()
            ])
            ->add('lieu', EntityType::class,[
                'class' => Lieu::class,
                'label' => "Lieu :",
                'placeholder' => '--- Sélectionner ---',
                'choice_label' => 'nom',
                'query_builder' => function (LieuRepository $lieuRepository){
                return $lieuRepository->createQueryBuilder('l')->orderBy('l.nom', 'ASC');
                },

                'constraints' => new NotBlank()
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
