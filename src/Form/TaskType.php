<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Title'
            ])
            ->add('dueDate', DateType::class, array(
                'label' => 'Deadline',
                'widget' => 'single_text',
                'required' => true,
            ))
            ->add('task', TextareaType::class, [
                'required' => true,
                'label' => 'Description',
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);

    }
}
