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
    Symfony\Component\Form\AbstractType as Form,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Instance extends Form
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('host', 'text')
            ->add('name', 'text')
            ->add('users', 'document', array(
                    'class' => 'Lilo\AppBundle\Document\User',
                    'query_builder' => function(DocumentRepository $er) {
                        return $er->createQueryBuilder('u');
                    },
                    'multiple' => true,
                    'expanded' => true
                )
            );
    }

    function getName()
    {
        return '_admin_instance';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Lilo\AppBundle\Document\Instance',
            )
        );
    }
}
