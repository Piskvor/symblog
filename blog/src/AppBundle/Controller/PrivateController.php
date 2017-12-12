<?php
declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Form\Type\BlogArticleType;
use AppBundle\Repository\BlogArticleRepository;
use /** @noinspection PhpUnusedAliasInspection - used by annotations */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\BlogArticle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use \DateTime;

class PrivateController extends Controller
{

    /**
     * @Route("/private/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {

        $blogArticle = new BlogArticle();
        $blogArticle->setArticleDate(new DateTime());
        $blogArticle->setArticleShown(false);
        $blogArticle->setTitle('');
        $blogArticle->setUrl('');
        $blogArticle->setArticleText('Lorem ipsum dolor sit amet...');

        return $this->formAction($request, $blogArticle);

    }

    /**
     * @Route("/private/edit", name="edit")
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        $id = (int)$request->query->get('id');

        /** @var BlogArticle $blogArticle */
        $blogArticle = $this->getDoctrine()
            ->getRepository(BlogArticle::class)
            ->find($id);

        if (!$blogArticle) {
            throw $this->createNotFoundException(
                'No article found for id ' . $id
            );
        }
        return $this->formAction($request, $blogArticle);
    }

    /**
     * @param Request $request
     * @param BlogArticle $blogArticle
     * @return Response
     */
    public function formAction(Request $request, BlogArticle $blogArticle)
    {

        $form = $this->createForm(BlogArticleType::class, $blogArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogArticle = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($blogArticle);
            $em->flush();

            if ($blogArticle->getId()) {
                return $this->redirectToRoute('edit', array('id' => $blogArticle->getId()));
            }
        }
        return $this->render('private/baseForm.html.twig', array('form' => $form->createView()));

    }

    /**
     * @Route("/private", name="private")
     * @Route("/private/{page}", name="list", requirements={"page" = "[0-9]*"}, defaults={"page" = "1"})
     * @param int $page
     * @return Response
     */
    public function listAction($page = 1) {
        if ($page < 1) {
            $page = 1;
        }
        /** @var BlogArticleRepository $blogArticleRepo */
        $blogArticleRepo = $this->getDoctrine()
            ->getRepository(BlogArticle::class);
        $articles = $blogArticleRepo->findBy(array(

        ), array(
            'articleDate' => 'DESC'
        ));
        $itemCount = $this->getParameter('articles_per_page_private');
        $totalPages = (int)ceil(count($articles) / $itemCount);
        $startOffset = (int)floor(($page - 1) * $itemCount);
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
        return $this->render('private/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'articles' => $chosenArticles,
            'route' => 'list',
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

}