<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 03.03.2017
 * Time: 19:49
 */

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationType extends AbstractType
{

    /** @var  Translator */
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

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
        )->add(
            'email',
            TextType::class,
            array(
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle',
            )
        )->add(
            'plainPassword',
            'repeated',
            array(
                'type' => 'password',
                'options' => array(
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'placeholder' => $this->translator->trans('app.user.password_placeholder'),
                        'tooltip' => 'app.user.password_tooltip',
                    ),
                ),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
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
