<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.03.2017
 * Time: 14:35
 */

namespace AppBundle\Form\Type\Filter;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;

class FilterType extends AbstractType
{

    /** @var  Translator */
    protected $translator;

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(Translator $translator, EntityManager $entityManager)
    {
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function getName()
    {
        return 'app_filter';
    }
}
