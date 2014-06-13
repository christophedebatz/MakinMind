<?php

namespace MakinMind\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use MakinMind\ResourceBundle\Form\ResourceType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('shortDescription', 'textarea')
            ->add('longDescription', 'textarea')
            ->add('beginDate', 'date')
            ->add('endingDate', 'date')
            //->add('logo', new ResourceType())
            ->add('domains', 'entity', array(
                                            'class' => 'MakinMindProjectBundle:Domains',
                                            'property' => 'name',
                                            'multiple' => true))
            ->add('applyTerms', new ApplyTermsType())
        ;
    }

    public function getName()
    {
        return 'makinmind_projectbundle_projecttype';
    }
}
