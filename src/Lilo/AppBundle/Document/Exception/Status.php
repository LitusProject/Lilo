<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene and @krmarien.
 *
 * @author Pieter Maene <pieter.maene@vtk.be>
 * @author Kristof Mariën <kristof.marien@vtk.be>
 */

namespace Lilo\AppBundle\Document\Exception;

use DateTime,
    Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    Lilo\AppBundle\Document\User as UserDocument,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\EmbeddedDocument
 */
class Status
{
    public static $possibleValues = array(
        'active'    => 'active',
        'closed'    => 'closed',
        'forwarded' => 'forwarded',
        'open'      => 'open'
    );

    /**
     * @ODM\Date
     */
    private $creationTime;

    /**
     * @ODM\ReferenceOne(targetDocument="Lilo\AppBundle\Document\User")
     */
    private $user;

    /**
     * @ODM\Field(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $value;

    public function __construct(UserDocument $user, $value)
    {
        $this->setUser($user);
        $this->setValue($value);
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

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(UserDocument $user)
    {
        $this->user = $user;
        return $this;
    }

    public function getValue()
    {
        if (!array_key_exists($this->value, self::$possibleValues))
            throw new \RuntimeException(sprintf('Invalid value retrieved (value: %s)', $this->value));

        return $this->value;
    }

    public function setValue($value)
    {
        if (!array_key_exists($value, self::$possibleValues))
            throw new \RuntimeException(sprintf('Invalid value given (value: %s)', $value));

        $this->value = $value;
        return $this;
    }
}
