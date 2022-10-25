<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnulationSortieType extends \Symfony\Component\Form\AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motifAnnulation', textareaType::class, [
                'label' => 'Motif : ',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'method'=> 'POST'
        ]);
    }

}