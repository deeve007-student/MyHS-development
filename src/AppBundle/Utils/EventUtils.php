<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 06.06.2017
 * Time: 11:46
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Event;
use AppBundle\Entity\UnavailableBlock;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\Translation\Translator;

class EventUtils
{
    /** @var  Hasher */
    protected $hasher;

    /** @var  Translator */
    protected $translator;


    public function __construct(
        Hasher $hasher,
        Translator $translator
    )
    {
        $this->hasher = $hasher;
        $this->translator = $translator;
    }

    public function getInterval()
    {
        return '15';
    }

    public function getDayStart()
    {
        return '08:00';
    }

    public function getDayEnd()
    {
        return '20:00';
    }

    public function getBusinessDayStart()
    {
        return '10:00';
    }

    public function getBusinessDayEnd()
    {
        return '18:00';
    }

    public function serializeEvent(Event $event)
    {
        $eventData = array(
            'id' => $this->hasher->encodeObject($event, ClassUtils::getParentClass($event)),
            'class' => get_class($event),
            'title' => (string)$event,
            'tag' => null,
            'description' => $event->getDescription() ? $event->getDescription() : '',
            'start' => $event->getStart()->format(\DateTime::ATOM),
            'end' => $event->getEnd()->format(\DateTime::ATOM),
            'column' => 0,
            'editable' => 1,
            'color' => '#D3D3D3',
            'textColor' => '#000',
        );

        switch (get_class($event)) {
            case Appointment::class:
                $eventData['tag'] = (string)$event->getTreatment();

                if ($color = $event->getTreatment()->getCalendarColour()) {
                    $eventData['color'] = $color;
                    $eventData['textColor'] = '#fff';
                }
                break;
            case UnavailableBlock::class:
                $eventData['tag'] = $this->translator->trans('app.unavailable_block.tag');
                break;
        }

        return $eventData;
    }

}
