<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Controller;

use Lilo\AppBundle\Component\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
}
