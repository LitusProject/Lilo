<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\ApiBundle\Component\Security\Authentication\Provider;

use Lilo\ApiBundle\Component\Security\Authentication\Token\KeyToken,
    Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface,
    Symfony\Component\Security\Core\Authentication\Token\TokenInterface,
    Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\User\UserProviderInterface,
    Symfony\Component\Security\Core\Exception\AuthenticationException;

class KeyProvider implements AuthenticationProviderInterface
{
    private $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function authenticate(TokenInterface $token)
    {
        if (!$token->getUser() instanceof UserInterface) {
            $user = $this->userProvider->loadUserByUsername($token->getUser());

            if (
                null === $user
                    || $token->getKey() != $user->getKey()
                    || $token->getHost() != gethostbyname($user->getHost())
            ) {
                throw new AuthenticationException('Key authentication failed');
            }

            $token = new KeyToken(
                $user->getHost(),
                $user->getKey(),
                $user->getRoles()
            );
            $token->setUser($user);
        } else {
            $user = $this->userProvider->loadUserByUsername($token->getKey());

            if ($user !== $token->getUser())
                throw new AuthenticationException('Key authentication failed');
        }

        return $token;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof KeyToken;
    }
}
