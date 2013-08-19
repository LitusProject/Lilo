<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Form;

use Symfony\Component\Form\AbstractType as Form,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Symfony\Component\Validator\Constraints\Email,
    Symfony\Component\Validator\Constraints\NotBlank;

class Install extends Form
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', array(
                'attr' => array(
                    'placeholder' => 'Username'
                ),
                'constraints' => array(
                    new NotBlank()
                ),
                'required' => true
            ))
            ->add('firstname', 'text', array(
                'attr' => array('placeholder' => 'First Name'),
                'constraints' => array(
                    new NotBlank()
                ),
                'required' => true
            ))
            ->add('lastname', 'text', array(
                'attr' => array('placeholder' => 'Last Name'),
                'constraints' => array(
                    new NotBlank()
                ),
                'required' => true))
            ->add('email', 'text', array(
                'attr' => array('placeholder' => 'Email'),
                'constraints' => array(
                    new NotBlank(),
                    new Email()
                ),
                'required' => true
            ))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'first_options' => array(
                    'attr' => array(
                        'class' => 'col-span-12',
                        'placeholder' => 'Password'
                    ),
                    'constraints' => array(
                        new NotBlank()
                    )
                ),
                'second_options' => array(
                    'attr' => array(
                        'class' => 'col-span-12',
                        'placeholder' => 'Repeat Password'
                    ),
                    'constraints' => array(
                        new NotBlank()
                    )
                ),
                'required' => true,
                'invalid_message' => 'The passwords did not match.'
            )
        );
    }

    function getName()
    {
        return '_install';
    }
}
