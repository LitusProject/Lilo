<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene and @krmarien.
 *
 * @author Pieter Maene <pieter.maene@vtk.be>
 * @author Kristof Mariën <kristof.marien@vtk.be>
 */

namespace Lilo\AppBundle\Component\Security\Authentication\Provider;

use Doctrine\ODM\MongoDB\DocumentManager,
    Doctrine\ODM\MongoDB\DocumentRepository,
    Symfony\Component\Security\Core\Exception\UsernameNotFoundException,
    Symfony\Component\Security\Core\Exception\UnsupportedUserException,
    Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\User\UserProviderInterface;

class DocumentUserProvider implements UserProviderInterface
{
    private $_class;
    private $_repository;
    private $_property;

    public function __construct(DocumentManager $dm, $class, $property = '')
    {
        $this->_class = $class;

        if (strpos($this->_class, ':'))
            $this->_class = $dm->getClassMetadata($class)->getName();

        $this->_repository = $dm->getRepository($class);
        $this->_property = $property;
    }

    public function loadUserByUsername($username)
    {
        if ('' != $this->_property) {
            $user = $this->_repository->findOneBy(array($this->_property => $username));
        } else {
            if (!$this->_repository instanceof UserProviderInterface)
                throw new \InvalidArgumentException(sprintf('The repository "%s" must implement UserProviderInterface', get_class($this->_repository)));

            $user = $this->_repository->loadUserByUsername($username);
        }

        if (null === $user)
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof $this->_class)
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported', get_class($user)));

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class == $this->_class;
    }
}