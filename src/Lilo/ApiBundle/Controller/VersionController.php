<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\ApiBundle\Controller;

use Lilo\AppBundle\Component\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Symfony\Component\HttpFoundation\Response;

class VersionController extends Controller
{
    /**
     * @Route("/version", name="_api_version")
     * @Route("/version/index", name="_api_version_index")
     */
    public function indexAction()
    {
        return new Response('1.0');
    }
}
