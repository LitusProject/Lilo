<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\AppBundle\Document\Exception;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\EmbeddedDocument
 */
class Trace
{
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
    private $function;

    /**
     * @ODM\Field(type="hash")
     */
    private $arguments;

    public function __construct($file, $line, $class, $function, array $arguments)
    {
        $this->setFile($file);
        $this->setLine($line);
        $this->setClass($class);
        $this->setFunction($function);
        $this->setArguments($arguments);
    }

    public function getFile($basename = false)
    {
        return $basename ? basename($this->file) : $this->file;
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

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    public function getFunction()
    {
        return $this->function;
    }

    public function setFunction($function)
    {
        $this->function = $function;
        return $this;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }
}
