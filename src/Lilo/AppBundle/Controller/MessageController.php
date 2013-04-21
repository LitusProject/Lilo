<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene and @krmarien.
 *
 * @author Pieter Maene <pieter.maene@vtk.be>
 * @author Kristof Mariën <kristof.marien@vtk.be>
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
