<?php
declare(strict_types=1);

namespace AppBundle\Controller;

use AdamQuaile\Bundle\FieldsetBundle\Form\FieldsetType;
use AppBundle\Form\Type\TagType;
use Doctrine\ORM\Query\Expr\Select;
use /** @noinspection PhpUnusedAliasInspection - used by annotations */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\BlogArticle;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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

        /** @var FormBuilder $form */
        $formBuilder = $this->createFormBuilder($blogArticle);
        if ($blogArticle->getId()) {
            $actionName = 'update';
            $formBuilder->add('id', HiddenType::class);
        } else {
            $actionName = 'create';
        }
        $formBuilder->add('title', TextType::class, array(
            'attr' => array(
                'class' => 'long-text')))
            ->add('url', TextType::class, array(
                'attr' => array(
                    'class' => 'long-text')))
            ->add('articleDate', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ))
            ->add('articleShown', ChoiceType::class, array(
                'choices' => array("Hidden" => 0, "Published" => 1), 'expanded' => true))
            ->add('articleText', TextareaType::class)
            ->add('tags', CollectionType::class, array(
                'entry_type' => TagType::class,
                'entry_options' => array('label' => false),
            ))
            ->add('buttons', FieldsetType::class, [
                'label' => false,
                'legend' => '',
                'fields' => function (FormBuilderInterface $fbuilder) use ($actionName) {
                    $fbuilder
                        ->add('save', SubmitType::class, array('label' => $actionName . ' article', 'attr' => array('class' => 'button-' . $actionName)))
                        ->add('cancel', ButtonType::class, array('label' => 'back to list', 'attr' => array('class' => 'button-cancel')));
                }
            ]);;

        /** @var Form $form */
        $form = $formBuilder->getForm();
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