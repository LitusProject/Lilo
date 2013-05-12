<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene and @krmarien.
 *
 * @author Pieter Maene <pieter.maene@vtk.be>
 * @author Kristof Mariën <kristof.marien@vtk.be>
 */

namespace Lilo\ApiBundle\Component\Security\Http\Firewall;

use Lilo\ApiBundle\Component\Security\Authentication\Token\KeyToken,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpKernel\Event\GetResponseEvent,
    Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface,
    Symfony\Component\Security\Core\Exception\AuthenticationException,
    Symfony\Component\Security\Core\SecurityContextInterface,
    Symfony\Component\Security\Http\Firewall\ListenerInterface;

class KeyListener implements ListenerInterface
{
    private $securityContext;
    private $authenticationManager;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(GetResponseEvent $event)
    {
        try {
            $request = $event->getRequest();

            $this->securityContext->setToken(
                $this->authenticationManager->authenticate(
                    new KeyToken(
                        $request->getClientIp(), $request->request->get('key')
                    )
                )
            );
        } catch (AuthenticationException $e) {
            $response = new Response();
            $response->setStatusCode(403);
            $event->setResponse($response);
        }
    }
}
