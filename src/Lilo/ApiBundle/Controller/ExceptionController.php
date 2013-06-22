<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\ApiBundle\Controller;

use Lilo\AppBundle\Component\Controller\Controller,
    Lilo\AppBundle\Document\Exception,
    Lilo\AppBundle\Document\Exception\Environment,
    Lilo\AppBundle\Document\Exception\Trace,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\Security\Core\SecurityContext;

class ExceptionController extends Controller
{
    /**
     * @Method("POST")
     * @Route("/exception/add", name="_api_exception_add")
     */
    public function addAction()
    {
        $data = $this->getRequest()->request->get('data');
        if ('' == $data)
            return new Response('No exception data was supplied', 500);

        $this->getDoctrine()->getManager()->persist(
            $this->createException(
                (array) json_decode($data)
            )
        );
        $this->getDoctrine()->getManager()->flush();

        return new Response('The exception was successfully stored');
    }

    private function createException(array $data)
    {
        $exception = null;
        if (count($data) > 0) {
            $exception = new Exception(
                $this->container->get('security.context')->getToken()->getUser(),
                $data['class'],
                $data['message'],
                $data['code'],
                $data['file'],
                $data['line']
            );

            if (isset($data['trace'])) {
                foreach ($data['trace'] as $line) {
                    $line = (array) $line;
                    $exception->addTrace(
                        new Trace(
                            $line['file'],
                            $line['line'],
                            $line['class'],
                            $line['function'],
                            (array) $line['args']
                        )
                    );
                }
            }

            if (isset($data['previous'])) {
                $exception->setPrevious(
                    $this->createException((array) $data['previous'])
                );
            }

            if (isset($data['environment'])) {
                $exception->setEnvironment(
                    new Environment(
                        $data['environment']['userAgent'],
                        $data['environment']['person'],
                        $data['environment']['session']
                    )
                );
            }
        }

        return $exception;
    }
}
