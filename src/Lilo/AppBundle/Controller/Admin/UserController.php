<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene and @krmarien.
 *
 * @author Pieter Maene <pieter.maene@vtk.be>
 * @author Kristof Mariën <kristof.marien@vtk.be>
 */

namespace Lilo\AppBundle\Controller\Admin;

use Lilo\AppBundle\Component\Controller\Controller,
    Lilo\AppBundle\Document\User,
    Lilo\AppBundle\Form\Admin\User as UserForm,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * @Route("/user/add", name="_admin_user_add")
     */
    public function addAction()
    {
        $user = new User(
            $this->container->get('security.secure_random')
        );

        $userForm = $this->createForm(new UserForm(), $user);

        if ($this->getRequest()->isMethod('POST')) {
            $userForm->bind($this->getRequest());

            if ($userForm->isValid()) {
                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();

                $this->getRequest()->getSession()->getFlashBag()->add(
                    'success',
                    'The user was succussfully created!'
                );

                return $this->redirect(
                    $this->generateUrl('_admin_user_manage')
                );
            }
        }

        return $this->render(
            'LiloAppBundle:Admin/User:add.html.twig',
            array(
                'userForm' => $userForm->createView()
            )
        );
    }

    /**
     * @Route("/user/manage", name="_admin_user_manage")
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
     * @Route("/bus/edit/{id}", name="_admin_bus_edit")
     * @ParamConverter("bus", class="BusesAppBundle:Bus")
     */
    public function editAction(Bus $bus)
    {
        $busForm = $this->createForm(new BusForm(), $bus);

        if ($this->getRequest()->isMethod('POST')) {
            $busForm->bind($this->getRequest());

            if ($busForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $this->getRequest()->getSession()->getFlashBag()->add(
                    'success',
                    'The bus was succussfully updated!'
                );

                return $this->redirect(
                    $this->generateUrl('_admin_bus_manage')
                );
            }
        }

        return $this->render(
            'BusesAppBundle:Admin/Bus:edit.html.twig',
            array(
                'busForm' => $busForm->createView()
            )
        );
    }

    /**
     * @Route("/bus/delete/{id}", name="_admin_bus_delete")
     * @ParamConverter("bus", class="BusesAppBundle:Bus")
     */
    public function deleteAction(Bus $bus)
    {
        $this->getDoctrine()->getManager()->remove($bus);
        $this->getDoctrine()->getManager()->flush();

        $this->getRequest()->getSession()->getFlashBag()->add(
            'success',
            'The bus was succussfully removed!'
        );

        return $this->redirect(
            $this->generateUrl('_admin_bus_manage')
        );
    }
}
