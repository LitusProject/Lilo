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
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    /**
     * @Route("/message", name="_message_index")
     */
    public function indexAction()
    {
        return $this->render(
            'LiloAppBundle:Message:index.html.twig'
        );
    }

    /**
     * @Method("POST")
     * @Route("/message/observed", name="_message_observed")
     */
    public function readAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest())
            return new Response('This actions require a XmlHttpRequest', 500);

        $this->getDoctrine()->getManager()
            ->getRepository('Lilo\AppBundle\Document\Message')
            ->findOneById($this->getRequest()->request->get('id'))
            ->addObserver($this->getUser());
        $this->getDoctrine()->getManager()->flush();

        return new Response('The message was successfully observed', 200);
    }
}
