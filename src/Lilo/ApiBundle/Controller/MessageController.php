<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\ApiBundle\Controller;

use Lilo\AppBundle\Component\Controller\Controller,
    Lilo\AppBundle\Document\Message,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\Security\Core\SecurityContext;

class MessageController extends Controller
{
    /**
     * @Method("POST")
     * @Route("/message/add", name="_api_message_add")
     */
    public function addAction()
    {
        $data = $this->getRequest()->request->get('data');
        if ('' == $data)
            return new Response('No message data was supplied', 500);

        $data = (array) json_decode($data);

        $this->getDoctrine()->getManager()->persist(
            new Message(
                $this->container->get('security.context')->getToken()->getUser(),
                $data['message'],
                $data['tags']
            )
        );
        $this->getDoctrine()->getManager()->flush();

        return new Response('The message was successfully stored');
    }
}
