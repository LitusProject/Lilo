<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Component\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController,
    Symfony\Component\HttpFoundation\Response;

class Controller extends SymfonyController
{
    public function getDoctrine()
    {
        return $this->get('doctrine_mongodb');
    }

    public function getUser()
    {
        return $this->get('security.context')->getToken()->getUser();
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $parameters['instances'] = $this->getDoctrine()->getManager()
            ->getRepository('Lilo\AppBundle\Document\Instance')
            ->findAll();

        foreach($parameters['instances'] as $instance)
            $instance->setDocumentManager($this->getDoctrine()->getManager());

        return parent::render($view, $parameters, $response);
    }
}
