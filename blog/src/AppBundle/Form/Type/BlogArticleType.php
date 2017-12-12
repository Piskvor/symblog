<?php
declare(strict_types=1);

namespace AppBundle\Form\Type;

use AdamQuaile\Bundle\FieldsetBundle\Form\FieldsetType;
use AppBundle\Entity\BlogArticle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        if ($formBuilder->getData()->getId()) {
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
            ->add('articleDate', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm',
                'attr' => array(
                    'class' => 'datetime-picker')
            ))
            ->add('articleShown', ChoiceType::class, array(
                'choices' => array("No (hidden)" => 0, "Yes (published)" => 1), 'expanded' => true))
            ->add('articleText', TextareaType::class, array(
                'attr' => array(
                    'class' => 'tinymce')))
            ->add('tags', CollectionType::class, array(
                'entry_type' => ArticleTagType::class,
                'entry_options' => array(
                    'label' => false,
                ),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('buttons', FieldsetType::class, [
                'label' => false,
                'legend' => '',
                'fields' => function (FormBuilderInterface $fbuilder) use ($actionName) {
                    $fbuilder
                        ->add('save', SubmitType::class, array('label' => $actionName . ' article', 'attr' => array('class' => 'button-' . $actionName)))
                        ->add('cancel', ButtonType::class, array('label' => 'back to list', 'attr' => array('class' => 'button-cancel')));
                }
            ]);

        return $formBuilder->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BlogArticle::class,
        ));
    }
}