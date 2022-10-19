<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('dateLimiteInscription')
            ->add('nbInscriptionsMax')
            ->add('duree')
            ->add('infosSortie')
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'label' => "Lieu :",
                'placeholder' => '--- Sélectionner ---',
                'choice_label' => 'nom',
                'query_builder' => function (LieuRepository $lieuRepository){
                    return $lieuRepository->createQueryBuilder('l')->orderBy('l.nom', 'ASC');
                },

                'constraints' => new NotBlank()
            ])



            //->add('siteOrganisateur') id campus
            //->add('ville')
            //->add('motifAnnulation')
            //->add('participants')
            //->add('organisateur')

            //->add('etat')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
