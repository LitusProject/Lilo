<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Component\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;

class Controller extends SymfonyController
{
    public function getDoctrine()
    {
        return $this->get('doctrine_mongodb');
    }
}
