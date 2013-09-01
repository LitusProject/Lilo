<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Document;

use DateInterval,
    DateTime,
    Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ODM\MongoDB\DocumentManager,
    Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    Lilo\AppBundle\Document\User as UserDocument,
    Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\Util\SecureRandom,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\Document(
 *     collection="instances",
 *     repositoryClass="Lilo\AppBundle\Repository\Instance"
 * )
 */
class Instance implements UserInterface
{
    /**
     * @ODM\Id
     */
    private $id;

    /**
     * @ODM\Field(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Regex("/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/")
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
     * @ODM\ReferenceMany(targetDocument="Lilo\AppBundle\Document\User")
     */
    private $users;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    private $_documentManager;

    public function __construct(SecureRandom $generator, $host = '', $name = '', array $users = array())
    {
        $this->setHost($host);
        $this->setName($name);

        $this->users = new ArrayCollection();
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
        $this->key = bin2hex($generator->nextBytes(16));
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

    public function getRoles()
    {
        return array(
            'ROLE_INSTANCE'
        );
    }

    public function getPassword() {}
    public function getSalt() {}
    public function getUsername() {}

    public function eraseCredentials() {}

    public function __toString()
    {
        return $this->host;
    }

    public function setDocumentManager(DocumentManager $documentManager)
    {
        $this->_documentManager = $documentManager;
        return $this;
    }

    public function getNumberUnread(UserDocument $user)
    {
        $since = new DateTime();
        $since->sub(new DateInterval('P1W'));

        return $this->_documentManager
                ->getRepository('Lilo\AppBundle\Document\Exception')
                ->findNumberUnreadSince($since, $this, $user) +
            $this->_documentManager
                ->getRepository('Lilo\AppBundle\Document\Message')
                ->findNumberUnreadSince($since, $this, $user);
    }
}
