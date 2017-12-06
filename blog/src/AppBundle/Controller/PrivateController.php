<?php
declare(strict_types=1);


namespace AppBundle\Controller;

use /** @noinspection PhpUnusedAliasInspection - used by annotations */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\BlogArticle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use \DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilder;

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

        /*return $this->editAction($request, $blogArticle);

    }

    /**
     * @Route("/private/edit", name="edit")
     * @param Request $request
     * @return Response
     *
    public function editAction(Request $request, $blogArticle)
    {
        */
//        var_dump($request);exit;
        /** @var FormBuilder $form */
        $formBuilder = $this->createFormBuilder($blogArticle);
        $formBuilder->add('title', TextType::class)
            ->add('url', TextType::class)
            ->add('articleDate', DateType::class)
            ->add('articleShown', ChoiceType::class, array('choices' => array("Hidden" => 0, "Published" => 1), 'expanded' => true))
            ->add('articleText', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'create article'));
        if ($blogArticle->getId()) {
            $form->
            add('id', TextType::class);
        }

        /** @var Form $form */
        $form = $formBuilder->getForm();
        //var_export($form->isSubmitted());exit;
        if ($form->isSubmitted()) {
            if( $form->isValid()) {
                $blogArticle = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($blogArticle);
                $em->flush();

                var_dump($blogArticle->getId());exit;
                return $this->redirectToRoute('edit', array('id' => $blogArticle->getId()));
            }
             else {
                var_dump($form->getErrors());exit;
             }
        }
        return $this->render('private/create.html.twig', array('form' => $form->createView()));

    }

}