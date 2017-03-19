<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 23:56
 */

namespace UserBundle\Form\Type;

use AppBundle\Form\Type\CountryFieldType;
use AppBundle\Form\Type\SubscriptionFieldType;
use AppBundle\Form\Type\TimezoneFieldType;
use AppBundle\Form\Type\TitleFieldType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationConfirmedType extends AbstractType
{

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'title',
            TitleFieldType::class
        )->add(
            'firstLogin',
            HiddenType::class,
            array(
                'data' => '0',
            )
        )->add(
            'firstName',
            TextType::class,
            array(
                'label' => 'app.user.first_name',
                'required' => true,
            )
        )->add(
            'lastName',
            TextType::class,
            array(
                'label' => 'app.user.last_name',
                'required' => true,
            )
        )->add(
            'country',
            CountryFieldType::class,
            array(
                'required' => false,
            )
        )->add(
            'timezone',
            TimezoneFieldType::class
        )->add(
            'agree',
            CheckboxType::class,
            array(
                'mapped' => false,
                'label' => 'app.agree_with_terms',
                'constraints' => array(
                    new IsTrue(),
                ),
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'UserBundle\Entity\User',
                'validation_groups' => array('Start'),
            )
        );
    }

    public function getName()
    {
        return 'app_user_registration_confirmed';
    }

}
