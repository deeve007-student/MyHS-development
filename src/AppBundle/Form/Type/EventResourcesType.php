<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 31.03.2017
 * Time: 15:37
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventResourcesType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'label' => 'app.event_resource.plural_label',
                'required' => false,
                'entry_type' => new EventResourceType(),
                'delete_empty' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'error_bubbling' => false,
            )
        );
    }

    public function getParent()
    {
        return CollectionType::class;
    }

    public function getName()
    {
        return 'app_event_resources';
    }

}
