<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.02.18
 * Time: 18:49
 */


namespace AppBundle\Twig;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\TreatmentNote;
use AppBundle\Utils\Hasher;
use Symfony\Component\Translation\Translator;

class TreatmentNoteExtension extends \Twig_Extension
{

    /** @var Translator */
    protected $translator;

    /** @var Hasher */
    protected $hasher;

    /** @var FormatterExtension */
    protected $formatterExtension;

    public function __construct(
        Translator $translator,
        Hasher $hasher,
        FormatterExtension $formatterExtension
    )
    {
        $this->translator = $translator;
        $this->hasher = $hasher;
        $this->formatterExtension = $formatterExtension;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('app_treatment_note_name', array($this, 'treatmentNoteName')),
        );
    }

    public function treatmentNoteName(TreatmentNote $treatmentNote)
    {
        $string = $treatmentNote->getTemplate() . ' &middot; ' . $treatmentNote;

        if ($treatmentNote->getAppointment()) {
            $url = '<a class="app-appointment-view treatment-note-header" href="#"
                   data-entity-id="' . $this->hasher->encodeObject($treatmentNote->getAppointment(), $treatmentNote->getAppointment()->getEventClass()) . '">' . $this->formatterExtension->dateFilter($treatmentNote->getAppointment()->getStart()) . '</a>';

            $string = $url . ' &middot; ' . $treatmentNote->getAppointment()->getTreatment() . ' (' . $treatmentNote->getAppointment()->getTreatment()->getCode() . ')';
            $string .= ' &middot; ' . $treatmentNote->getAppointment()->getOwner();

        }

        return $string;
    }
}
