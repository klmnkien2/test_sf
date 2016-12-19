<?php

namespace Gao\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', array('label' => 'Username'))
            ->add('password', 'password')
            ->add('phone', 'text')
            ->add('fullName', 'text')
            ->add('email', 'email')
            ->add('role', 'choice', array(
                'choices'  => array(
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN'
                )
            ))
            ->add('submit', 'submit');
    }

    public function getName()
    {
        return 'admin';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gao\AdminBundle\Entity\Admin',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        ));
    }

}