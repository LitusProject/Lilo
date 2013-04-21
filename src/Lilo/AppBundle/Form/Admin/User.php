<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene and @krmarien.
 *
 * @author Pieter Maene <pieter.maene@vtk.be>
 * @author Kristof Mariën <kristof.marien@vtk.be>
 */

namespace Lilo\AppBundle\Form\Admin;

use Doctrine\ODM\MongoDB\DocumentRepository,
    Lilo\AppBundle\Document\User as UserDocument,
    Symfony\Component\Form\AbstractType as Form,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class User extends Form
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text')
            ->add('firstname', 'text')
            ->add('lastname', 'text')
            ->add('email', 'text')
            ->add('roles', 'choice', array(
                    'choices' => UserDocument::$possibleRoles
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
