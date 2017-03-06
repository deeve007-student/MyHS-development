<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 03.03.2017
 * Time: 19:49
 */

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'username',
            HiddenType::class,
            array(
                'required' => false,
                'data' => uniqid(),
            )
        )->add(
            'businessName',
            TextType::class,
            array(
                'label' => 'app.user.business_name',
                'required' => true,
            )
        );
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'app_user_registration';
    }

}
