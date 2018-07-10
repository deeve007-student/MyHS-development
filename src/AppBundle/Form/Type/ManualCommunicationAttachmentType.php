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

/**
 * Class ManualCommunicationAttachmentType
 */
class ManualCommunicationAttachmentType extends AbstractType
{

    Use AddFieldOptionsTrait;

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'file',
            FileType::class,
            array(
                'label' => 'app.attachment.file',
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\ManualCommunicationAttachment',
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_manual_communication_attachment';
    }

}
