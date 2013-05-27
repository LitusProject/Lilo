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
}
