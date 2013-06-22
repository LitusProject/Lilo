<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Controller;

use Lilo\AppBundle\Component\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ExceptionController extends Controller
{
    /**
     * @Route("/exception", name="_exception_index")
     */
    public function indexAction()
    {
        return $this->render(
            'LiloAppBundle:Exception:index.html.twig'
        );
    }

    /**
     * @Method("POST")
     * @Route("/exception/observed", name="_exception_observed")
     */
    public function readAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest())
            return new Response('This action requires a XmlHttpRequest', 500);

        $this->getDoctrine()->getManager()
            ->getRepository('Lilo\AppBundle\Document\Exception')
            ->findOneById($this->getRequest()->request->get('id'))
            ->addObserver($this->get('security.context')->getToken()->getUser());
        $this->getDoctrine()->flush();

        return new Response('The exception was successfully observed', 200);
    }
}
