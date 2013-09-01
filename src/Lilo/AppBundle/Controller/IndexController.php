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
    Lilo\AppBundle\Document\Instance,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Symfony\Component\Security\Core\SecurityContext;

class IndexController extends Controller
{
    /**
     * @Route("/{id}", name="_index_index", defaults={"id"=null})
     * @ParamConverter("instance", class="LiloAppBundle:Instance")
     */
    public function indexAction(Instance $instance = null)
    {
        $since = new DateTime();
        $since->sub(new DateInterval('P2W'));

        $exceptions = $this->getDoctrine()->getManager()
            ->getRepository('Lilo\AppBundle\Document\Exception')
            ->findAllSince($since, $instance);

        $messages = $this->getDoctrine()->getManager()
            ->getRepository('Lilo\AppBundle\Document\Message')
            ->findAllSince($since, $instance);

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
