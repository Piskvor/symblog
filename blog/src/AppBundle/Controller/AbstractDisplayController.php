<?php
declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\BlogArticle;
use AppBundle\Repository\BlogArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AbstractDisplayController extends Controller
{

    /**
     * @return BlogArticle[]
     */
    protected function getArticles()
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
        $blogArticle->incrementViews();
        return $blogArticle;
    }

    /**
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
        $blogArticle->incrementViews();
        return $blogArticle;
    }
}