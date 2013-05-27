<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
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

            if ('' == $request->request->get('key'))
                throw new AuthenticationException('No API key was supplied');

            $this->securityContext->setToken(
                $this->authenticationManager->authenticate(
                    new KeyToken(
                        $request->getClientIp(), $request->request->get('key')
                    )
                )
            );
        } catch (AuthenticationException $e) {
            $event->setResponse(
                new Response($e->getMessage(), 403)
            );
        }
    }
}
