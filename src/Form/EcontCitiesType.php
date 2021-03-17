<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EcontCitiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        ($options['data']['officesFrom']['offices']);
        $builder
            ->add('cityFrom', ChoiceType::class, [
                'placeholder' => 'Select city',
                'choices' => $options['data']['citiesName'],
                'required' => true,
            ])
            ->add('officeFrom', ChoiceType::class, [
                'choices' => $options['data']['officesFrom'],
                'required' => false
            ])
            ->add('cityTo', ChoiceType::class, [
                'placeholder' => 'Select city',
                'choices' => $options['data']['citiesName'],
                'required' => true
            ])
            ->add('officeTo', ChoiceType::class, [
                'choices' => $options['data']['officesTo'],
                'required' => false
            ])
            ->add('weight', TextType::class, [
                'required' => false,
                'label' => 'Weight',
                'attr' => [
                    'placeholder' => 'Enter weight in kg'
                ]
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
