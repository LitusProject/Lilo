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
