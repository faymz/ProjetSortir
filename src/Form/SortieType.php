<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'label' => 'Nombre de places : '
            ])
            ->add('duree', timeType::class, [
                'label' => 'Durée : ',
                'html5' => true,
                'widget' => 'single_text'
            ] )
            ->add('infosSortie', textareaType::class, [
                'label' => 'Description et infos : '
            ])
            ->add('ville', EntityType::class,[
                'class' => Ville::class,
                'label' => "Ville :",
                'placeholder' => '--- Sélectionner ---',
                'choice_label' => 'nom'
            ])
            ->add('lieu', ChoiceType::class, [
                'placeholder' => 'lieu [choisir une ville]'
            ])

        ;

        $formModifier = function (FormInterface $form, Ville $ville = null){
            $lieu = (null === $ville) ? [] : $ville->getLieux();
            $form->add('lieu', EntityType::class,[
                'class' => Lieu::class,
                'choices' => $lieu,
                'choice_label' => 'nom',
                'placeholder' => 'lieu [choisir une ville]',
                'label' => "Lieu : "
            ]);
        };

        $builder->get('ville')->addEventListener(
          FormEvents::POST_SUBMIT,
            function (FormEvent $eventVille) use ($formModifier){
              $ville = $eventVille->getForm()->getData();
              $formModifier($eventVille->getForm()->getParent(), $ville);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
