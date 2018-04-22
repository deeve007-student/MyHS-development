<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 13:10
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\DocumentCategory;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class DocumentType extends AbstractType
{

    Use AddFieldOptionsTrait;

    /** @var EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categoriesQb = $this->entityManager->getRepository('AppBundle:DocumentCategory')->createQueryBuilder('c');
        /** @var DocumentCategory[] $categories */
        $categories = $categoriesQb->addOrderBy('c.defaultCategory', 'DESC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()->getResult();
        $categoriesArray = array();

        $categoriesArray['new'] = 'app.document_category.new';
        foreach ($categories as $category) {
            $categoriesArray[$category->getId()] = $category->getName();
        }

        $builder->add(
            'file',
            FileType::class,
            array(
                'label' => 'app.attachment.file',
            )
        )->add(
            'categorySelector',
            ChoiceType::class,
            array(
                'label' => 'app.document_category.label',
                'mapped' => false,
                'choices' => $categoriesArray,
                'required' => true,
                'attr' => array(
                    'class' => 'app-document-category-selector',
                ),
            )
        )->add(
            'newCategory',
            TextType::class,
            array(
                'label' => 'app.document_category.new_category',
                'required' => false,
                'mapped' => false,
            )
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data['categorySelector'] == 'new') {
                $this->addFieldOptions($form, 'newCategory', array(
                    'constraints' => array(
                        new NotBlank(),
                    )
                ));
            }

        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Document',
            )
        );
    }

    public function getName()
    {
        return 'app_document';
    }

}
