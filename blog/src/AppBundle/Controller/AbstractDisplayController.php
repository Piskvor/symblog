<?php
declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\BlogArticle;
use AppBundle\Repository\BlogArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AbstractDisplayController extends Controller
{

    /**
     * Return the articles that are user-visible
     * @return BlogArticle[]
     */
    protected function getShownArticles()
    {
        /** @var BlogArticleRepository $blogArticleRepo */
        $blogArticleRepo = $this->getDoctrine()
            ->getRepository(BlogArticle::class);
        return $blogArticleRepo->findBy(array(
            'articleShown' => 1
        ), array(
            'articleDate' => 'DESC'
        ));

    }

    /**
     * Given an article slug, return that article (if any)
     * @param string $slug
     * @return BlogArticle|null
     */
    protected function getArticleBySlug($slug)
    {
        /** @var BlogArticleRepository $blogArticleRepo */
        $blogArticleRepo = $this->getDoctrine()
            ->getRepository(BlogArticle::class);
        /** @var BlogArticle $blogArticle */
        $blogArticle = $blogArticleRepo->findOneBy(array(
            'url' => $slug,
            'articleShown' => 1
        ));
        if (!$blogArticle) { // retry as an ID
            return null;
        }
        $this->incrementViews($blogArticle);
        return $blogArticle;
    }

    /**
     * Given an article ID, return that article (if any)
     * @param int $id
     * @return BlogArticle|null
     */
    protected function getArticleById($id)
    {
        $blogArticle = null;
        /** @var BlogArticleRepository $blogArticleRepo */
        $blogArticleRepo = $this->getDoctrine()
            ->getRepository(BlogArticle::class);
        if ($id > 0) {
            /** @var BlogArticle $blogArticle */
            $blogArticle = $blogArticleRepo->findOneBy(array(
                'id' => $id,
                'articleShown' => 1
            ));
        }
        if (!$blogArticle) {
            return null;
        }
        $this->incrementViews($blogArticle);
        return $blogArticle;
    }

    /**
     * Set the default caching headers - note that articles need to be cached privately!
     * @param Response $response
     * @param bool $shared
     * @return Response
     */
    protected function markCacheable($response, $shared = true)
    {
        $response->setEtag(hash('md5', $response->getContent()));
        if ($shared) {
            $response->setSharedMaxAge($this->getParameter('shared_cache_seconds'));
        } else {
            $response->setMaxAge($this->getParameter('private_cache_seconds'));
        }
        return $response;
    }

    /**
     * Increment the view count for a given article
     * @param BlogArticle $blogArticle
     */
    private function incrementViews($blogArticle)
    {
        $blogArticle->incrementViews();
        $em = $this->getDoctrine()->getManager();
        $em->persist($blogArticle);
        $em->flush();
    }
}