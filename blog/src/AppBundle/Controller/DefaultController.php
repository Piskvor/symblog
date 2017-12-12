<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ArticleTag;
use AppBundle\Entity\BlogArticle;
use /** @noinspection PhpUnusedAliasInspection - used by annotations */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractDisplayController
{
    /**
     * @Route("/{page}", requirements={"page" = "[0-9]*"}, defaults={"page" = "1"},  name="index")
     * @param int $page
     * @return Response
     */
    public function indexAction($page = 1)
    {
        $blogArticles = $this->getArticles();
        return $this->commonRender('index', $blogArticles, $page);
    }

    /**
     * @Route("/a/{slug}", requirements={"slug" = "[a-zA-Z0-9._-]+"}, defaults={"slug" = ""}, name="article")
     * @param string $slug
     * @return Response
     */
    public function articleAction($slug) {
        // strip page extension if provided
        $extension = $this->getParameter('page_extension');
        $extensionPosition = strripos($slug, $extension);
        if ($extensionPosition > 0) {
            $slugShortenedLength = strlen($slug) - strlen($extension);
            if ($extensionPosition === $slugShortenedLength) {
                $slug = substr($slug, 0, $slugShortenedLength);
            }
        }
        $blogArticle = $this->getArticleBySlug($slug);
        if (!$blogArticle) {
            $blogArticle = $this->getArticleById($slug);
        }
        if (!$blogArticle) {
            $this->redirectToRoute('homepage');
        }
        return $this->render('default/article.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'article' => $blogArticle
        ]);
    }

    /**
     * @Route("/tagged/{tagName}", requirements={"tagName" = "[a-zA-Z0-9._-]+"}, defaults={"tagName" = ""}, name="tagged")
     * @Route("/tagged/{tagName}/{page}", requirements={"page" = "[0-9]*"}, defaults={"page" = "1"}, requirements={"tagName" = "[a-zA-Z0-9._-]+"}, defaults={"tagName" = ""}, name="tag")
     * @param string $tagName
     * @param int $page
     * @return Response
     */
    public function tagAction($tagName, $page = 1) {
        $tagRepo = $this->getDoctrine()
            ->getRepository(ArticleTag::class);
        /** @var ArticleTag $tag */
        $tag = $tagRepo->findOneBy(array(
            'name' => $tagName,
            'shown' => 1
        ));
        if ($tag) {
            $articles = $tag->getArticles();
        } else {
            $articles = null;
        }
        return $this->commonRender('tag', $articles, $page, $tagName);
    }

    /**
     * @param string $route
     * @param \Doctrine\Common\Collections\Collection|BlogArticle[] $articles
     * @param int $page
     * @param string|null $tagName
     * @return Response
     */
    private function commonRender($route, $articles, $page = 1, $tagName = null)
    {
        if ($page < 1) {
            $page = 1;
        }
        $itemCount = $this->getParameter('articles_per_page');
        $totalPages = ceil(count($articles) / $itemCount);
        $startOffset = floor(($page - 1) * $itemCount);
        if (!empty($articles)) {
            if (is_array($articles)) {
                $articlesArray = $articles;
            } else {
                $articlesArray = $articles->toArray();
            }
            $chosenArticles = array_slice($articlesArray, $startOffset, $itemCount);
        } else {
            $chosenArticles = array();
        }
        return $this->render('default/' . $route . '.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'route' => $route,
            'tag' => $tagName,
            'articles' => $chosenArticles,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }


}
