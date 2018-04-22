<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.03.2017
 * Time: 13:22
 */

namespace AppBundle\Form\Type\Filter;

use AppBundle\Entity\DocumentCategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DocumentFilterType extends FilterType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var DocumentCategory[] $categories */
        $categories = $this->entityManager->getRepository('AppBundle:DocumentCategory')->createQueryBuilder('c')
            ->addOrderBy('c.defaultCategory', 'DESC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()->getResult();

        $categoriesArr = array();
        foreach ($categories as $category) {
            $categoriesArr[$category->getId()] = $this->translator->trans((string)$category);
        }

        $builder->add(
            'string',
            TextType::class,
            array(
                'required' => false,
                'label' => false,
                'attr' => array('placeholder' => 'app.document.filter.string'),
            )
        )->add(
            'category',
            ChoiceType::class,
            array(
                'required' => false,
                'label' => false,
                'multiple' => true,
                'expanded' => true,
                'choices' => $categoriesArr,
            )
        );

    }

    public function getParent()
    {
        return 'app_filter';
    }

    public function getName()
    {
        return 'app_document_filter';
    }

}
