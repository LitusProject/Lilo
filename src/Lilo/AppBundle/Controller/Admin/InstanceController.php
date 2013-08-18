<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Controller\Admin;

use Lilo\AppBundle\Component\Controller\Controller,
    Lilo\AppBundle\Document\Instance,
    Lilo\AppBundle\Form\Admin\Instance as InstanceForm,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class InstanceController extends Controller
{
    /**
     * @Route("/instance/add", name="_admin_instance_add")
     */
    public function addAction()
    {
        $instance = new Instance(
            $this->container->get('security.secure_random')
        );

        $instanceForm = $this->createForm(new InstanceForm(), $instance);

        if ($this->getRequest()->isMethod('POST')) {
            $instanceForm->bind($this->getRequest());

            if ($instanceForm->isValid()) {
                foreach ($instance->getUsers() as $user)
                    $user->addInstance($instance);

                $this->getDoctrine()->getManager()->persist($instance);
                $this->getDoctrine()->getManager()->flush();

                $this->getRequest()->getSession()->getFlashBag()->add(
                    'success',
                    'The instance was succussfully created!'
                );

                return $this->redirect(
                    $this->generateUrl('_admin_instance_manage')
                );
            }
        }

        return $this->render(
            'LiloAppBundle:Admin/Instance:add.html.twig',
            array(
                'instanceForm' => $instanceForm->createView()
            )
        );
    }

    /**
     * @Route("/instance/manage", name="_admin_instance_manage")
     */
    public function manageAction()
    {
        $instances = $this->getDoctrine()->getManager()
            ->getRepository('Lilo\AppBundle\Document\Instance')
            ->findAll();

        return $this->render(
            'LiloAppBundle:Admin/Instance:manage.html.twig',
            array(
                'instances' => $instances
            )
        );
    }

    /**
     * @Route("/instance/edit/{id}", name="_admin_instance_edit")
     * @ParamConverter("instance", class="LiloAppBundle:Instance")
     */
    public function editAction(Instance $instance)
    {
        $instanceForm = $this->createForm(new InstanceForm(), $instance);

        if ($this->getRequest()->isMethod('POST')) {
            $instanceForm->bind($this->getRequest());

            if ($instanceForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $this->getRequest()->getSession()->getFlashBag()->add(
                    'success',
                    'The instance was succussfully updated!'
                );

                return $this->redirect(
                    $this->generateUrl('_admin_instance_manage')
                );
            }
        }

        return $this->render(
            'LiloAppBundle:Admin/Instance:edit.html.twig',
            array(
                'instanceForm' => $instanceForm->createView()
            )
        );
    }

    /**
     * @Route("/instance/delete/{id}", name="_admin_instance_delete")
     * @ParamConverter("instance", class="LiloAppBundle:Instance")
     */
    public function deleteAction(Instance $instance)
    {
        $this->getDoctrine()->getManager()->remove($instance);
        $this->getDoctrine()->getManager()->flush();

        $this->getRequest()->getSession()->getFlashBag()->add(
            'success',
            'The instance was succussfully removed!'
        );

        return $this->redirect(
            $this->generateUrl('_admin_instance_manage')
        );
    }
}
