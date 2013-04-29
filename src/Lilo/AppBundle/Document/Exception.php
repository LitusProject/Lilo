<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene and @krmarien.
 *
 * @author Pieter Maene <pieter.maene@vtk.be>
 * @author Kristof Mariën <kristof.marien@vtk.be>
 */

namespace Lilo\AppBundle\Document;

use Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    Lilo\AppBundle\Document\Exception\Trace as TraceDocument,
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
     * @ODM\ReferenceMany(targetDocument="Lilo\AppBundle\Document\Trace")
     */
    private $trace;

    /**
     * @ODM\ReferenceOne(targetDocument="Lilo\AppBundle\Document\Exception")
     */
    private $previous;

    public function __construct($message, $code, $file, $line, array $trace, Exception $previous)
    {
        $this->setMessage($message);
        $this->setCode($code);
        $this->setFile($file);
        $this->setLine($line);

        $this->trace = new ArrayCollection();
        foreach ($trace as $line)
            $this->addTrace($line);

        $this->setPrevious($previous);

    }

    public function getId()
    {
        return $this->id;
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

    public function setPrevious(Exception $previous)
    {
        $this->previous = $previous;
        return $this;
    }
}
