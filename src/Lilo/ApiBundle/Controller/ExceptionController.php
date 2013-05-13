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
    Lilo\AppBundle\Document\Exception,
    Lilo\AppBundle\Document\Exception\Environment,
    Lilo\AppBundle\Document\Exception\Trace,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\Security\Core\SecurityContext;

class ExceptionController extends Controller
{
    /**
     * @Route("/exception/add", name="_api_exception_add")
     */
    public function addAction()
    {
        $this->getDoctrine()->getManager()->persist(
            $this->createException(
                (array) json_decode($this->getRequest()->request->get('data'))
            )
        );

        return new Response(
            json_encode(
                array(
                    'status' => 'success'
                )
            )
        );
    }

    private function createException(array $data)
    {
        $exception = null;
        if (count($data) > 0) {
            $exception = new Exception(
                $this->container->get('security.context')->getToken()->getUser(),
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
