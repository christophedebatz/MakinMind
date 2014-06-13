<?php

namespace MakinMind\ResourceBundle\Form;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ResourceFormType extends BaseType
{
    protected function buildResourceForm(FormBuilder $builder, array $options)
    {
        parent::buildUserForm($builder, $options);
        $builder->add('file', 'file');       
    }
}