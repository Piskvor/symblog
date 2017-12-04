<?php
declare(strict_types=1);

namespace AppBundle\Entity;

use /** @noinspection PhpUnusedAliasInspection - used by annotations */
    Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_user")
 */
class BlogUser implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=128)
     * @var string
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=128)
     * @var string
     */
    private $hash;
    /**
     * @ORM\Column(type="string", length=64)
     * @var string
     */
    private $salt;
    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @var bool
     */
    private $isActive;

    /**
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt(string $salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return array('ROLE_ADMIN'); // TODO: add user role mapping if required - FOSBundle?
    }

    public function eraseCredentials()
    {
        $this->setHash('*');
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->hash,
            $this->salt,
        ));
    }

    /** @see \Serializable::unserialize()
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->hash,
            $this->salt
            ) = unserialize($serialized);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    public function getPassword()
    {
        return $this->getHash();
    }

    /**
     * @param string $hash
     */
    public function setHash(string $hash)
    {
        $this->hash = $hash;
    }

}