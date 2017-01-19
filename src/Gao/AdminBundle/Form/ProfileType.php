<?php

namespace Gao\AdminBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileType extends AbstractType
{

    /**
     * EntityManager.
     */
    protected $em;

    /**
     * Container Interface.
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param EntityManager $em        The EntityManager.
     * @param Container     $container The Container Interface.
     */
    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', 'password')
            ->add('phone', 'text')
            ->add('fullName', 'text')
            ->add('email', 'email')
            ->add('submit', 'submit');

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $form->add('newPassword', 'password', array('mapped' => false, 'required' => false));
                $form->add('retypePassword', 'password', array('mapped' => false, 'required' => false));
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_BIND,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();
                $admin = $this->container->get('security.context')->getToken()->getUser();
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($admin);
                $password = $encoder->encodePassword($data['password'], $admin->getSalt());
                if ($admin->getPassword() != $password) {
                    $form->addError(new FormError("Password isn't true."));
                    return false;
                }
                if (!empty($data['newPassword']) && !empty($data['retypePassword']) && $data['newPassword'] != $data['retypePassword']) {
                    $form->addError(new FormError("New password and Retype password aren't mapped."));
                    return false;
                }

                if (!empty($data['newPassword'])) {
                    $password = $encoder->encodePassword($data['newPassword'], $admin->getSalt());
                }

                $data['password'] = $password;
                $event->setData($data);
            }
        );
    }

    public function getName()
    {
        return 'profile';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gao\AdminBundle\Entity\Admin',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'admin_item',
        ));
    }

}