<?php
declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use /** @noinspection PhpUnusedAliasInspection - used by annotations */
    Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="blog_article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BlogArticleRepository")
 */
class BlogArticle
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=1024)
     * @var string
     */
    private $title;
    /**
     * @ORM\Column(type="string", length=1024)
     * @var string
     */
    private $url;
    /**
     * @ORM\Column(name="article_text", type="string")
     * @var string
     */
    private $articleText;
    /**
     * @ORM\Column(name="article_date", type="date")
     * @var \DateTime
     */
    private $articleDate;
    /**
     * @ORM\Column(name="article_views", type="integer")
     * @var int
     */
    private $articleViews = 0;
    /**
     * @ORM\Column(name="article_shown", type="boolean")
     * @var bool
     */
    private $articleShown = true;
    /**
     * @var \Doctrine\Common\Collections\Collection|ArticleTag[]
     *
     * @ORM\ManyToMany(targetEntity="ArticleTag")
     * @ORM\JoinTable(
     *  name="blog_article_tags",
     *  joinColumns={
     *      @ORM\JoinColumn(name="blog_article_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="article_tag_id", referencedColumnName="id")
     *  }
     * )
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function addTag(ArticleTag $tag)
    {
        $this->tags->add($tag);
    }

    public function removeTag(ArticleTag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * caveat: race condition; if this is an issue, we could use alternate counting, e.g. nginx access log -> redis -> periodic update into db (minimal overhead, works with static content)
     */
    public function incrementViews() {
        $this->articleViews += 1;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getArticleViews(): int
    {
        return $this->articleViews;
    }

    /**
     * @param int $articleViews
     */
    public function setArticleViews(int $articleViews)
    {
        $this->articleViews = $articleViews;
    }

    /**
     * @return int
     */
    public function getId()
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getArticleText(): string
    {
        return $this->articleText;
    }

    /**
     * @param string $articleText
     */
    public function setArticleText(string $articleText)
    {
        $this->articleText = $articleText;
    }

    /**
     * @return \DateTime
     */
    public function getArticleDate(): \DateTime
    {
        return $this->articleDate;
    }

    /**
     * @param \DateTime $articleDate
     */
    public function setArticleDate(\DateTime $articleDate)
    {
        $this->articleDate = $articleDate;
    }

    /**
     * @return bool
     */
    public function isArticleShown(): bool
    {
        return $this->articleShown;
    }

    /**
     * @param bool $articleShown
     */
    public function setArticleShown(bool $articleShown)
    {
        $this->articleShown = $articleShown;
    }

    /**
     * @return ArticleTag[]|\Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param ArticleTag[]|\Doctrine\Common\Collections\Collection $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

}