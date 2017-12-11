<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ArticleTag;
use AppBundle\Entity\BlogArticle;
use AppBundle\Repository\BlogArticleRepository;
use /** @noinspection PhpUnusedAliasInspection - used by annotations */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/{page}", requirements={"page" = "[0-9]*"}, defaults={"page" = "1"},  name="homepage")
     * @return Response
     */
    public function indexAction($page)
    {
        /** @var BlogArticleRepository $blogArticleRepo */
        $blogArticleRepo = $this->getDoctrine()
            ->getRepository(BlogArticle::class);
        $blogArticles = $blogArticleRepo->findBy(array(
            'articleShown' => 1
        ), array(
            'articleDate' => 'DESC'
        ));
        return $this->commonRender('default/index.html.twig', $blogArticles, $page);
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
        /** @var BlogArticleRepository $blogArticleRepo */
        $blogArticleRepo = $this->getDoctrine()
            ->getRepository(BlogArticle::class);
        $blogArticle = $blogArticleRepo->findOneBy(array(
            'url' => $slug,
            'articleShown' => 1
        ));
        if (!$blogArticle) {
            $id = (int)$slug;
            if ($id > 0) {
                $blogArticle = $blogArticleRepo->findOneBy(array(
                    'id' => $id,
                    'articleShown' => 1
                ));
            }
            if (!$blogArticle) {
                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render('default/article.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'article' => $blogArticle
        ]);
    }

    /**
     * @Route("/tagged/{tagName}", requirements={"tagName" = "[a-zA-Z0-9._-]+"}, defaults={"tagName" = "", "page" = "1"}, name="tagged")
     * @Route("/tagged/{tagName}/{page}", requirements={"page" = "[0-9]*"}, defaults={"page" = "1"}, requirements={"tagName" = "[a-zA-Z0-9._-]+"}, defaults={"tagName" = ""}, name="taggedPage")
     * @param string $tagName
     * @return Response
     */
    public function tagAction($tagName, $page) {
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
        return $this->commonRender('default/tag.html.twig', $articles, $page, $tag);
    }

    private function commonRender($view, $articles, $page, $tag = null)
    {
        $itemCount = $this->getParameter('articles_per_page');
        $totalPages = ceil(count($articles) / $itemCount);
        $startOffset = floor($page - 1 * $itemCount);
        $chosenArticles = array_slice($articles, $startOffset, $itemCount);
        return $this->render($view, [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'tag' => $tag,
            'articles' => $chosenArticles,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }


}
