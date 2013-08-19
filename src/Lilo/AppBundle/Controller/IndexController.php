<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Controller;

use DateInterval,
    DateTime,
    Lilo\AppBundle\Component\Controller\Controller,
    Lilo\AppBundle\Document\Exception,
    Lilo\AppBundle\Document\Message,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Symfony\Component\Security\Core\SecurityContext;

class IndexController extends Controller
{
    /**
     * @Route("/", name="_index_index")
     */
    public function indexAction()
    {
        $since = new DateTime();
        $since->sub(new DateInterval('P1W'));

        $exceptions = $this->getDoctrine()->getManager()
            ->getRepository('Lilo\AppBundle\Document\Exception')
            ->findAllSince($since);

        $messages = $this->getDoctrine()->getManager()
            ->getRepository('Lilo\AppBundle\Document\Message')
            ->findAllSince($since);

        $data = array_merge($exceptions, $messages);
        usort($data, function($a, $b) {
            if ($a->getCreationTime() == $b->getCreationTime())
                return 0;
            return ($a->getCreationTime() > $b->getCreationTime()) ? -1 : 1;
        });

        $timeline = array();
        foreach($data as $item) {
            $timeline[$item->getCreationTime()->format('j/m/Y')][] = array(
                'type' => $item instanceof Exception ? 'exception' : 'message',
                'item' => $item
            );
        }

        return $this->render(
            'LiloAppBundle:Index:index.html.twig',
            array(
                'timeline' => $timeline
            )
        );
    }
}
