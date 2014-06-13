<?php

namespace MakinMind\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ContractTypesType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('description', 'textarea')
            ->add('file')
        ;
    }

    public function getName()
    {
        return 'makinmind_projectbundle_contracttypestype';
    }
}
