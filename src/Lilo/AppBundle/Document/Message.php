<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene and @krmarien.
 *
 * @author Pieter Maene <pieter.maene@vtk.be>
 * @author Kristof Mariën <kristof.marien@vtk.be>
 */

namespace Lilo\AppBundle\Document;

use DateTime,
    Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    Lilo\AppBundle\Document\Instance as InstanceDocument,
    Lilo\AppBundle\Document\User as UserDocument,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\Document(
 *     collection="messages",
 *     repositoryClass="Lilo\AppBundle\Repository\Message"
 * )
 */
class Message
{
    /**
     * @ODM\Id
     */
    private $id;

    /**
     * @ODM\Date
     */
    private $creationTime;

    /**
     * @ODM\ReferenceOne(targetDocument="Lilo\AppBundle\Document\Instance")
     */
    private $instance;

    /**
     * @ODM\ReferenceMany(targetDocument="Lilo\AppBundle\Document\User")
     */
    private $observers;

    /**
     * @ODM\Field(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $message;

    /**
     * @ODM\Field(type="hash")
     */
    private $tags;

    public function __construct(InstanceDocument $instance, $message, array $tags)
    {
        $this->setCreationTime(new DateTime());
        $this->setInstance($instance);

        $this->observers = new ArrayCollection();

        $this->setMessage($message);
        $this->setTags($tags);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getInstance()
    {
        return $this->instance;
    }

    public function setInstance(InstanceDocument $instance)
    {
        $this->instance = $instance;
        return $this;
    }

    public function getObservers()
    {
        return $this->observers;
    }

    public function addObserver(UserDocument $observer)
    {
        $this->observers[] = $observer;
    }

    public function removeObserver(UserDocument $observer)
    {
        $this->observers->removeElement($observer);
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags(array $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * Set creationTime
     *
     * @param date $creationTime
     * @return \Message
     */
    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;
        return $this;
    }

    /**
     * Get creationTime
     *
     * @return date $creationTime
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }
}
