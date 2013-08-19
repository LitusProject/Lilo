<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Controller;

use Lilo\AppBundle\Component\Controller\Controller,
    Lilo\AppBundle\Document\User,
    Lilo\AppBundle\Form\Install as InstallForm,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Symfony\Component\HttpFoundation\Response;

class InstallController extends Controller
{
    /**
     * @Route("/install", name="_install_index")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getManager()
            ->getRepository('Lilo\AppBundle\Document\User')
            ->findAll();

        if (count($users) > 0) {
            return $this->redirect(
                $this->generateUrl('_index_index')
            );
        }

        $installForm = $this->createForm(new InstallForm());

        if ($this->getRequest()->isMethod('POST')) {
            $installForm->bind($this->getRequest());

            if ($installForm->isValid()) {
                $user = new User(
                    $this->container->get('security.secure_random'),
                    $installForm->getViewData()['username'],
                    $installForm->getViewData()['firstname'],
                    $installForm->getViewData()['lastname'],
                    $installForm->getViewData()['email'],
                    array(
                        'ROLE_ADMIN'
                    )
                );

                $user->setPassword(
                    $this->container->get('security.secure_random'),
                    $this->get('security.encoder_factory')->getEncoder($user),
                    $installForm->getViewData()['password']
                );

                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirect(
                    $this->generateUrl('_auth_login')
                );
            }
        }

        return $this->render(
            'LiloAppBundle:Install:index.html.twig',
            array(
                'installForm' => $installForm->createView()
            )
        );
    }
}
