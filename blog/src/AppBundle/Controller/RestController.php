<?php
declare(strict_types=1);


namespace AppBundle\Controller;

use AppBundle\Entity\BlogArticle;
use Symfony\Component\HttpFoundation\JsonResponse;
use /** @noinspection PhpUnusedAliasInspection - used by annotations */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class RestController extends AbstractDisplayController
{

    /**
     * @Route("/article", name="rest_articles")
     * @Route("/articles", name="rest_articles_")
     * @return Response
     */
    public function cgetAction()
    {
        $blogArticles = $this->getShownArticles();

        $result = array();
        /** @var BlogArticle $blogArticle */
        foreach ($blogArticles as $blogArticle) {
            $result[] = $this->getBaseResult($blogArticle);
        }
        $response = new JsonResponse(array(
            'articles' => $result
        ));
        return $this->markCacheable($response);
    }

    /**
     * @Route("/article/{id}", requirements={"id" = "[0-9]*"}, name="rest_article")
     * @Route("/articles/{id}", requirements={"id" = "[0-9]*"}, name="rest_article_")
     * @param int $id
     * @return Response
     */
    public function getAction($id)
    {
        $blogArticle = $this->getArticleById($id);

        if ($blogArticle) {
            $result = $this->getBaseResult($blogArticle);
            $result['text'] = $blogArticle->getArticleText();
        } else {
            $result = array();
        }
        $response = new JsonResponse($result, empty($result) ? 404 : 200);
        return $this->markCacheable($response);
    }

    /**
     * @param BlogArticle $blogArticle
     * @return array
     */
    private function getBaseResult($blogArticle)
    {
        return array(
            'id' =>     $blogArticle->getId(),
            'title' =>  $blogArticle->getTitle(),
            'date' =>   $blogArticle->getArticleDate(),
//            'views' =>   $blogArticle->getArticleViews(),
            'url' =>    $this->generateUrl(
                'article',
                array(
                    'slug' => $blogArticle->getUrl()
                )
            )
        );
    }


}