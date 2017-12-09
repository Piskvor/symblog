<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BlogArticle;
use AppBundle\Repository\BlogArticleRepository;
use /** @noinspection PhpUnusedAliasInspection - used by annotations */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/a/{slug}", requirements={"slug" = "[a-zA-Z0-9._-]+"}, defaults={"slug" = ""}, name="article")
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

}
