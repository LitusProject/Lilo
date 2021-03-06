<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Document;

use DateTime,
    Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    Lilo\AppBundle\Document\Exception as ExceptionDocument,
    Lilo\AppBundle\Document\Exception\Environment as EnvironmentDocument,
    Lilo\AppBundle\Document\Exception\Status as StatusDocument,
    Lilo\AppBundle\Document\Exception\Trace as TraceDocument,
    Lilo\AppBundle\Document\Instance as InstanceDocument,
    Lilo\AppBundle\Document\User as UserDocument,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\Document(
 *     collection="exceptions",
 *     repositoryClass="Lilo\AppBundle\Repository\Exception"
 * )
 */
class Exception
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
     * @ODM\EmbedMany(targetDocument="Lilo\AppBundle\Document\Exception\Status")
     */
    private $statuses;

    /**
     * @ODM\Field(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $class;

    /**
     * @ODM\Field(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $message;

    /**
     * @ODM\Field(type="int")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="int")
     */
    private $code;

    /**
     * @ODM\Field(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $file;

    /**
     * @ODM\Field(type="int")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="int")
     */
    private $line;

    /**
     * @ODM\EmbedMany(targetDocument="Lilo\AppBundle\Document\Exception\Trace")
     */
    private $trace;

    /**
     * @ODM\ReferenceOne(targetDocument="Lilo\AppBundle\Document\Exception")
     */
    private $previous;

    /**
     * @ODM\EmbedOne(targetDocument="Lilo\AppBundle\Document\Exception\Environment")
     */
    private $environment;

    public function __construct(InstanceDocument $instance, $class, $message, $code, $file, $line)
    {
        $this->setInstance($instance);
        $this->setCreationTime(new DateTime());

        $this->observers = new ArrayCollection();
        $this->statuses = new ArrayCollection();

        $this->setClass($class);
        $this->setMessage($message);
        $this->setCode($code);
        $this->setFile($file);
        $this->setLine($line);

        $this->trace = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreationTime()
    {
        return $this->creationTime;
    }

    public function setCreationTime(DateTime $creationTime)
    {
        $this->creationTime = $creationTime;
        return $this;
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

    public function getStatus($value)
    {
        foreach ($this->getStatuses() as $status) {
            if ($status->getValue() == $value)
                return $status;
        }

        return null;
    }

    public function getStatuses()
    {
        return $this->statuses;
    }

    public function addStatus(StatusDocument $status)
    {
        $this->statuses[] = $status;
    }

    public function removeStatus(StatusDocument $status)
    {
        $this->statuses->removeElement($status);
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        $this->class = $class;
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

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function setLine($line)
    {
        $this->line = $line;
        return $this;
    }

    public function getTrace()
    {
        return $this->trace;
    }

    public function addTrace(TraceDocument $trace)
    {
        $this->trace[] = $trace;
    }

    public function removeTrace(TraceDocument $trace)
    {
        $this->trace->removeElement($trace);
    }

    public function getPrevious()
    {
        return $this->previous;
    }

    public function setPrevious(ExceptionDocument $previous)
    {
        $this->previous = $previous;
        return $this;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function setEnvironment(EnvironmentDocument $environment)
    {
        $this->environment = $environment;
        return $this;
    }
}
