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
    Lilo\AppBundle\Form\Activate as ActivateForm,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Symfony\Component\Security\Core\SecurityContext;

class AuthController extends Controller
{
    /**
     * @Route("/auth/activate/{code}/", name="_auth_activate")
     * @ParamConverter("user", class="LiloAppBundle:User")
     */
    public function activateAction(User $user)
    {
        $activateForm = $this->createForm(new ActivateForm());

        if ($this->getRequest()->isMethod('POST')) {
            $activateForm->bind($this->getRequest());

            if ($activateForm->isValid()) {
                $user->setPassword(
                        $this->container->get('security.secure_random'),
                        $this->get('security.encoder_factory')->getEncoder($user),
                        $activateForm->getViewData()['password']
                    )
                    ->resetCode();

                $this->getDoctrine()->getManager()->flush();

                return $this->redirect(
                    $this->generateUrl('_auth_login')
                );
            }
        }

        return $this->render(
            'LiloAppBundle:Auth:activate.html.twig',
            array(
                'activateForm' => $activateForm->createView(),
                'user' => $user
            )
        );
    }

    /**
     * @Route("/auth/login", name="_auth_login")
     */
    public function loginAction()
    {
        if ($this->getRequest()->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->getRequest()->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->getRequest()->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
            $this->getRequest()->getSession()->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'LiloAppBundle:Auth:login.html.twig',
            array(
                'lastUsername' => $this->getRequest()->getSession()->get(SecurityContext::LAST_USERNAME),
                'error'        => $error,
            )
        );
    }
}
