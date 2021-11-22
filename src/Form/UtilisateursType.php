<?php

namespace App\Form;

use App\Entity\Carte;
use App\Entity\CimAm;
use App\Entity\Cimetiere;
use App\Entity\Utilisateurs;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomFam_ut',TextType::class,[
                'label'=>'Nom de famille :'
            ])
            ->add('nomUs_ut',TextType::class,[
                'label'=>'Nom d\'usage :',
                'required' => false
            ])
            ->add('pre_ut',TextType::class,[
        'label'=>'Prénoms :'
            ])
            ->add('dayBirth_ut',DateType::class,[
                'label'=>'Date de naissance :',
                'widget'=>'single_text'
            ])
            ->add('firstAdress_ut',TextType::class,[
                'label'=>'Adresse princiale :'
            ])
            ->add('compAdress_ut',TextType::class,[
                'label'=>'Complément d\'adresse :',
                'required'=> false
            ])
            ->add('city_ut',TextType::class,[
                'label'=>'Ville :'
            ])
            ->add('cp_ut',TextType::class,[
                'label'=>'Code postale :'
            ])
            ->add('id_card', TextType::class,[
                'data'=> uniqid(),
                'label'=>'Numéro de carte :',
                'disabled'=>true,
                'mapped'=>false
            ])
            ->add('cardVal', CheckboxType::class,[
                'mapped'=>false,
                'required'=>false,
                'label'=> 'Validité :'
            ])

            ->add('cimetieres', EntityType::class, [
               'label'=> 'Accés cimetières :',
              'required' => false,
             'mapped' => false,
            'class' => Cimetiere::class,
            'expanded' => true,
            'multiple' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
