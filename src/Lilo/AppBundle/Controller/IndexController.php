<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Controller;

use Lilo\AppBundle\Component\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Symfony\Component\Security\Core\SecurityContext;

class IndexController extends Controller
{
    /**
     * @Route("/", name="_index_index")
     */
    public function indexAction()
    {
        return $this->render(
            'LiloAppBundle:Index:index.html.twig'
        );
    }
}
