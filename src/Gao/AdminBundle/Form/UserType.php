<?php

namespace Gao\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', array('label' => 'Username'))
            ->add('password', 'password')
            ->add('phone', 'text')
            ->add('fullName', 'text')
            ->add('email', 'email')
            ->add('vcbAccNumber', 'text')
            ->add('submit', 'submit');
    }

    public function getName()
    {
        return 'admin';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gao\C5Bundle\Entity\Users',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        ));
    }

}