<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene and @krmarien.
 *
 * @author Pieter Maene <pieter.maene@vtk.be>
 * @author Kristof Mariën <kristof.marien@vtk.be>
 */

namespace Lilo\AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    Lilo\AppBundle\Repository\User as UserDocument,
    Symfony\Component\Security\Core\Util\SecureRandom,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\Document(repositoryClass="Lilo\AppBundle\Repository\Instance")
 */
class Instance
{
    /**
     * @ODM\Id
     */
    private $id;

    /**
     * @ODM\Field(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $host;

    /**
     * @ODM\Field(type="string")
     */
    private $key;

    /**
     * @ODM\Field(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $name;

    /**
     * @ODM\ReferenceMany(targetDocument="Lilo\AppBundle\Repository\User")
     */
    private $users;

    public function __construct(SecureRandom $generator, $host = '', $name = '', array $users = array())
    {
        $this->setHost($host);
        $this->setName($name);

        foreach ($users as $user)
            $this->addUser($user);

        $this->setKey($generator);
    }

    public function getId()
    {
        return $this->id;
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

    public function setKey(SecureRandom $generator)
    {
        $this->key = $generator->nextBytes(32);
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(UserDocument $user)
    {
        $this->users[] = $user;
    }

    public function removeUser(UserDocument $user)
    {
        $this->users->removeElement($user);
    }
}
