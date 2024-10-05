<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\PerformanceReview;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PerformanceReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reviewDate')
            ->add('rating')
            ->add('comment')
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => function (Employee $employee) {
                    return $employee->getId() . ' - ' . $employee->getFirstName() . ' ' . $employee->getLastName();
                },
            ])
            ->add('reviewer', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => function (Employee $employee) {
                    return $employee->getId() . ' - ' . $employee->getFirstName() . ' ' . $employee->getLastName();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PerformanceReview::class,
        ]);
    }
}
