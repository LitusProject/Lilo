<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\ApiBundle\Component\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class KeyToken extends AbstractToken
{
    private $host;
    private $key;

    public function __construct($host, $key, array $roles = array())
    {
        parent::__construct($roles);

        $this->setHost($host);
        $this->setKey($key);

        $this->setUser($key);
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function getCredentials()
    {
        return $this->key;
    }
}
