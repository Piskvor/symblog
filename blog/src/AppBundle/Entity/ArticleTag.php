<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var bool
     *
     * @ORM\Column(name="shown", type="boolean")
     */
    private $shown = true;
    /**
     * @var \Doctrine\Common\Collections\Collection|BlogArticle[]
     *
     * @ORM\ManyToMany(targetEntity="BlogArticle")
     * @ORM\JoinTable(
     *  name="blog_article_tags",
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="blog_article_id", referencedColumnName="id")
     *  },
     *  joinColumns={
     *      @ORM\JoinColumn(name="article_tag_id", referencedColumnName="id")
     *  }
     * )
     */
    private $articles;

    public function __construct($name = '')
    {
        $this->name = $name;
        $this->articles = new ArrayCollection();
    }

    /**
     * @return BlogArticle[]|\Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param BlogArticle[]|\Doctrine\Common\Collections\Collection $articles
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return bool
     */
    public function isShown(): bool
    {
        return $this->shown;
    }

    /**
     * @param bool $shown
     */
    public function setShown(bool $shown)
    {
        $this->shown = $shown;
    }

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

