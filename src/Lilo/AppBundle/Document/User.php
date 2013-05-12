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
    Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\Util\SecureRandom,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\Document(
 *     collection="users",
 *     repositoryClass="Lilo\AppBundle\Repository\User"
 * )
 */
class User implements UserInterface
{
    public static $possibleRoles = array(
        'ROLE_ADMIN' => 'Admin',
        'ROLE_USER'  => 'User'
    );

    /**
     * @ODM\Id
     */
    private $id;

    /**
     * @ODM\Field(type="string")
     * @ODM\Index(unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $username;

    /**
     * @ODM\Field(type="string", name="first_name")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $firstName;

    /**
     * @ODM\Field(type="string", name="last_name")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $lastName;

    /**
     * @ODM\Field(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ODM\Field(type="string")
     * @ODM\Index(unique=true)
     */
    private $code;

    /**
     * @ODM\Field(type="string")
     */
    private $salt;

    /**
     * @ODM\Field(type="string")
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min="8")
     */
    private $password;

    /**
     * @ODM\Field(type="hash")
     */
    private $roles;

    public function __construct(SecureRandom $generator, $username = '', $firstName = '', $lastName = '', $email = '', array $roles = array())
    {
        $this->setUsername($username);
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setEmail($email);
        $this->setRoles($roles);

        $this->setCode($generator);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode(SecureRandom $generator)
    {
        $this->code = bin2hex($generator->nextBytes(16));
        return $this;
    }

    public function resetCode()
    {
        $this->code = '';
        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt(SecureRandom $generator)
    {
        $this->salt = bin2hex($generator->nextBytes(16));
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(SecureRandom $generator, $encoder, $password)
    {
        $this->setSalt($generator);

        $this->password = $encoder->encodePassword($password, $this->getSalt());
        return $this;
    }

    public function getRoles()
    {
        foreach ($this->roles as $role) {
            if (!array_key_exists($role, self::$possibleRoles))
                throw new \RuntimeException(sprintf('Invalid role retrieved (username: %s, role: %s)', $this->username, $role));
        }

        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        foreach ($roles as $role) {
            if (!array_key_exists($role, self::$possibleRoles))
                throw new \RuntimeException(sprintf('Invalid role given (username: %s, role: %s)', $this->username, $role));
        }

        $this->roles = $roles;
        return $this;
    }

    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function eraseCredentials()
    {
        $this->salt = '';
        $this->password = '';
    }

    public function __toString()
    {
        return $this->getFullName();
    }
}
