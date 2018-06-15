<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 19:25
 */

namespace AppBundle\Form\Type;

use AppBundle\Form\Traits\EventTrait;
use AppBundle\Utils\EntityFactory;
use AppBundle\Utils\EventUtils;
use AppBundle\Utils\Formatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

/**
 * Class EventType
 */
class EventType extends AbstractType
{

    /** @var  EventUtils */
    protected $eventUtils;

    /** @var  Formatter */
    protected $formatter;

    /** @var  EntityFactory */
    protected $entityFactory;

    /** @var  Translator */
    protected $translator;

    /**
     * EventType constructor.
     * @param EventUtils $eventUtils
     * @param Formatter $formatter
     * @param EntityFactory $entityFactory
     * @param Translator $translator
     */
    public function __construct(EventUtils $eventUtils, Formatter $formatter, EntityFactory $entityFactory, Translator $translator)
    {
        $this->eventUtils = $eventUtils;
        $this->formatter = $formatter;
        $this->entityFactory = $entityFactory;
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_event';
    }

}
