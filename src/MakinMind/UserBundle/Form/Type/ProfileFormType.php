<?php

namespace MakinMind\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    protected function buildUserForm(FormBuilder $builder, array $options)
    {
        parent::buildUserForm($builder, $options);
        $builder->add('firstname')  
                ->add('lastname')
                ->add('birthdate', 'date', array('widget' => 'single_text', 'format' => 'dd-MM-yyyy', 'required'  => false))
                ->add('phone', 'number', array('required'  => false))
                ->add('address', 'text', array('required'  => false))
                ->add('city', 'text',  array('required'  => false))
                ->add('job', 'text', array('required'  => false))
                ->add('company', 'text', array('required'  => false))
                ->add('workSocialURL', 'text', array('required'  => false))
                ->add('facebookURL', 'text', array('required'  => false))
                ->add('twitterURL', 'text', array('required'  => false))
                ->add('city', 'text', array('required'  => false))
                ->add('zipcode', 'number', array('required'  => false))
                ->add('country', 'country', array('required'  => false));
                
    }

    public function getName()
    {
        return 'makinmind_user_profile';
    }
}