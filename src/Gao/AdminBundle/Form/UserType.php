<?php

namespace Gao\AdminBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * EntityManager.
     */
    protected $em;

    /**
     * Constructor.
     *
     * @param EntityManager $em The EntityManager.
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', array('label' => 'Username'))
            ->add('password', 'password')
            ->add('phone', 'text')
            ->add('fullName', 'text')
            ->add('email', 'email')
            ->add('vcbAccNumber', 'text')
            ->add('refId', 'hidden')
            ->add('submit', 'submit');

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                $referUsername = null;
                if (!empty($data->getRefId())) {
                    $user = $this->em->getRepository('GaoC5Bundle:Users')->find($data->getRefId());
                    $referUsername = $user->getUsername();
                }
                $form = $event->getForm();
                $form->add('referUsername', 'text', array('mapped' => false, 'data' => $referUsername));
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_BIND,
            function (FormEvent $event) {
                $data = $event->getData();
                $user = $this->em->getRepository('GaoC5Bundle:Users')->findOneBy(array( 'username' => $data['referUsername'] ));
                if (!empty($user)) {
                    $data['refId'] = $user->getId();
                    $event->setData($data);
                } else {
                    $form = $event->getForm();
                    $form->addError(new FormError("Username doesn't exist."));
                }
            }
        );
    }

    public function getName()
    {
        return 'user';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gao\C5Bundle\Entity\Users',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'user_item',
        ));
    }

}