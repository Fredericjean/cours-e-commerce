<?php

namespace App\Form;

use App\Entity\Taxe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TaxeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
               'label'=>'Nom',
               'attr'=> [
                'placeholder'=>'Nom de la taxe',
               ],
               'required'=>false
            ])
            ->add('rate', NumberType::class, [
                'label'=>'Taux',
                'attr'=> [
                    'placeholder'=>'Montant de la taxe (trop chère)'
                ],
                'required'=>true,
            ])
            ->add('enable', CheckboxType::class, [
                'label'=>'Actif',
                'required' =>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Taxe::class,
        ]);
    }
}
