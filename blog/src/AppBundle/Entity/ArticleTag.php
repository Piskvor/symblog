<?php

namespace AppBundle\Entity;

use /** @noinspection PhpUnusedAliasInspection - used by annotations */
    Doctrine\ORM\Mapping as ORM;

/**
 * ArticleTag
 *
 * @ORM\Table(name="article_tag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleTagRepository")
 */
class ArticleTag
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ArticleTag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}

