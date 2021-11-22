<?php

namespace App\Form;

use App\Entity\Gestionnaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class GestionnaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('iden_ges',TextType::class,[
                'label'=>'Identifiant du gestionnaire :'
                ])
            ->add('roles',ChoiceType::class,[
                'choices'=>[
                    'Gestionnaire' => 'ROLE_USER',
                    'Admin'=> 'ROLE_ADMIN',
                    'Super Admin'=> 'ROLE_SADMIN'
                ],
                'expanded'=>true,
                'multiple'=>true,
                'label'=>'Rôles :',
            ])
            #->add('password')
            ->add('nom_ges',TextType::class,[
                'label'=>'Nom du gestionnaire :'
            ])
            ->add('pren_ges',TextType::class,[
                'label'=>'Prénom dugestionnaire :'
            ])
            #->add('gere')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gestionnaire::class,
        ]);
    }
}
