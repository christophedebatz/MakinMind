<?php

namespace MakinMind\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ApplyTermsType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('pbStudies', 'integer')
            ->add('firstDiploma', 'text')
            ->add('secondDiploma', 'text')
            ->add('specialization', 'text')
            ->add('numberWorkedYears', 'integer')
            ->add('hoursPerWeek', 'integer')
            ->add('paralleleContract', 'checkbox', array('required' => false))
        ;
    }

    public function getName()
    {
        return 'makinmind_projectbundle_applytermstype';
    }
}
