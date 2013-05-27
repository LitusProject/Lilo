<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Component\Security\Authentication\Provider;

use Doctrine\ODM\MongoDB\DocumentManager,
    Doctrine\ODM\MongoDB\DocumentRepository,
    Symfony\Component\Security\Core\Exception\UsernameNotFoundException,
    Symfony\Component\Security\Core\Exception\UnsupportedUserException,
    Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\User\UserProviderInterface;

class DocumentProvider implements UserProviderInterface
{
    private $class;
    private $repository;
    private $property;

    public function __construct(DocumentManager $dm, $class, $property = '')
    {
        $this->class = $class;

        if (strpos($this->class, ':'))
            $this->class = $dm->getClassMetadata($class)->getName();

        $this->repository = $dm->getRepository($class);
        $this->property = $property;
    }

    public function loadUserByUsername($username)
    {
        if ('' != $this->property) {
            $user = $this->repository->findOneBy(array($this->property => $username));
        } else {
            if (!$this->repository instanceof UserProviderInterface)
                throw new \InvalidArgumentException(sprintf('The repository "%s" must implement UserProviderInterface', getclass($this->repository)));

            $user = $this->repository->loadUserByUsername($username);
        }

        if (null === $user)
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof $this->class)
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported', getclass($user)));

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class == $this->class;
    }
}