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
        $users = $this->getDoctrine()->getManager()
            ->getRepository('Lilo\AppBundle\Document\User')
            ->findAll();

        return $this->render(
            'LiloAppBundle:Admin/User:manage.html.twig',
            array(
                'users' => $users
            )
        );
    }

    /**
     * @Route("/user/edit/{username}", name="_admin_user_edit")
     * @ParamConverter("user", class="LiloAppBundle:User")
     */
    public function editAction(User $user)
    {
        $userForm = $this->createForm(new UserForm(), $user);

        if ($this->getRequest()->isMethod('POST')) {
            $userForm->bind($this->getRequest());

            if ($userForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $this->getRequest()->getSession()->getFlashBag()->add(
                    'success',
                    'The user was succussfully updated!'
                );

                return $this->redirect(
                    $this->generateUrl('_admin_user_manage')
                );
            }
        }

        return $this->render(
            'LiloAppBundle:Admin/User:edit.html.twig',
            array(
                'userForm' => $userForm->createView()
            )
        );
    }

    /**
     * @Route("/user/delete/{username}", name="_admin_user_delete")
     * @ParamConverter("user", class="LiloAppBundle:User")
     */
    public function deleteAction(User $user)
    {
        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();

        $this->getRequest()->getSession()->getFlashBag()->add(
            'success',
            'The user was succussfully removed!'
        );

        return $this->redirect(
            $this->generateUrl('_admin_user_manage')
        );
    }
}
