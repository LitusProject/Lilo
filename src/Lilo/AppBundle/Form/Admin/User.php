<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Form\Admin;

use Lilo\AppBundle\Document\User as UserDocument,
    Symfony\Component\Form\AbstractType as Form,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class User extends Form
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text')
            ->add('firstname', 'text', array('label' => 'First Name'))
            ->add('lastname', 'text', array('label' => 'Last Name'))
            ->add('email', 'text')
            ->add('roles', 'choice', array(
                    'choices'  => UserDocument::$possibleRoles,
                    'multiple' => true,
                    'expanded' => true,
                )
            );
    }

    function getName()
    {
        return '_admin_user';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Lilo\AppBundle\Document\User',
            )
        );
    }
}
