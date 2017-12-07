<?php
declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Form\Type\BlogArticleType;
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
        $blogArticleId = (int)$request->query->get('id');

        /** @var BlogArticle $blogArticle */
        $blogArticle = $this->getDoctrine()
            ->getRepository(BlogArticle::class)
            ->find($blogArticleId);

        if (!$blogArticle) {
            throw $this->createNotFoundException(
                'No article found for id ' . $blogArticleId
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

}