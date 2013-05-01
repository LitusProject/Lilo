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
    Lilo\AppBundle\Document\Instance as InstanceDocument,
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
     * @ODM\ReferenceOne(targetDocument="Lilo\AppBundle\Document\Instance")
     */
    private $instance;

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
        $this->setInstance($instance);
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
}
