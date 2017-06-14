<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 19:25
 */

namespace AppBundle\Form\Type;

use AppBundle\Form\Traits\EventTrait;
use AppBundle\Utils\EventUtils;
use AppBundle\Utils\Formatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{

    /** @var  EventUtils */
    protected $eventUtils;

    /** @var  Formatter */
    protected $formatter;

    public function __construct(EventUtils $eventUtils, Formatter $formatter)
    {
        $this->eventUtils = $eventUtils;
        $this->formatter = $formatter;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'error_mapping' => array(
                    'datesNotEqual' => 'end',
                    'endMoreThanStart' => 'end',
                ),
            )
        );
    }

    public function getName()
    {
        return 'app_event';
    }

}
