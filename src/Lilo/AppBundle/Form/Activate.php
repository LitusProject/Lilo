<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene and @krmarien.
 *
 * @author Pieter Maene <pieter.maene@vtk.be>
 * @author Kristof Mariën <kristof.marien@vtk.be>
 */

namespace Lilo\AppBundle\Form;

use Symfony\Component\Form\AbstractType as Form,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Activate extends Form
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', 'repeated', array(
                'type' => 'password',
                'first_options' => array(
                    'attr' => array(
                        'class' => 'col-span-12',
                        'placeholder' => 'Password'
                    )
                ),
                'second_options' => array(
                    'attr' => array(
                        'class' => 'col-span-12',
                        'placeholder' => 'Repeat Password'
                    )
                ),
                'required' => true,
                'invalid_message' => 'The passwords did not match.'
            )
        );
    }

    function getName()
    {
        return '_admin_activate';
    }
}
