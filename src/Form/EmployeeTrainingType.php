<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\EmployeeTraining;
use App\Entity\TrainingProgram;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeTrainingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('completionDate')
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => function (Employee $employee) {
                    return $employee->getId() . ' - ' . $employee->getFirstName() . ' ' . $employee->getLastName();
                },
            ])
            ->add('trainingProgram', EntityType::class, [
                'class' => TrainingProgram::class,
                'choice_label' => function (TrainingProgram $trainingProgram) {
                    return $trainingProgram->getId() . ' - ' . $trainingProgram->getProgramName();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmployeeTraining::class,
        ]);
    }
}
