<?php
declare(strict_types=1);


namespace AppBundle\Controller;

use AppBundle\Entity\BlogArticle;
use Symfony\Component\HttpFoundation\JsonResponse;
use /** @noinspection PhpUnusedAliasInspection - used by annotations */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RestController extends AbstractDisplayController
{

    /**
     * @Route("/articles", name="rest_articles")
     * @return JsonResponse
     */
    public function cgetAction()
    {
        $blogArticles = $this->getArticles();

        $result = array();
        /** @var BlogArticle $blogArticle */
        foreach ($blogArticles as $blogArticle) {
            $result[] = $this->getBaseResult($blogArticle);
        }
        return new JsonResponse(array(
            'articles' => $result
        ));
    }

    /**
     * @Route("/article/{id}", requirements={"id" = "[0-9]*"}, name="rest_article")
     * @param int $id
     * @return JsonResponse
     */
    public function getAction($id)
    {
        $blogArticle = $this->getArticleById($id);

        $result = $this->getBaseResult($blogArticle);
        $result['text'] = $blogArticle->getArticleText();
        return new JsonResponse($result);
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