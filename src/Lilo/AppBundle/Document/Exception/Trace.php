<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene and @krmarien.
 *
 * @author Pieter Maene <pieter.maene@vtk.be>
 * @author Kristof Mariën <kristof.marien@vtk.be>
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
    private $function;

    /**
     * @ODM\Field(type="hash")
     */
    private $arguments;

    public function __construct($file, $line, $function, array $arguments)
    {
        $this->setFile($file);
        $this->setLine($line);
        $this->setFunction($function);
        $this->setArguments($arguments);
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
