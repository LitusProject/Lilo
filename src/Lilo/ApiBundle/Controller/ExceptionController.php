<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene and @krmarien.
 *
 * @author Pieter Maene <pieter.maene@vtk.be>
 * @author Kristof Mariën <kristof.marien@vtk.be>
 */

namespace Lilo\ApiBundle\Controller;

use Lilo\AppBundle\Component\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Symfony\Component\Security\Core\SecurityContext;

class ExceptionController extends Controller
{
    /**
     * @Route("/exception/add", name="_api_exception_add")
     */
    public function addAction()
    {
        return $this->render(
            'LiloApiBundle:Exception:add.html.twig'
        );
    }
}
