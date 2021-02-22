<?php

namespace App\Form;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EcontCitiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('from', ChoiceType::class, [
                'choices' => $options['data'],
            ])
            ->add('to', ChoiceType::class, [
                'choices' => $options['data'],
            ])
            ->add('submit', SubmitType::class)
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);

    }
}
