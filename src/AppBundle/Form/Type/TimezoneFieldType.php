<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 10:00
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimezoneFieldType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $timezones = function () {
            $choices = array();
            foreach (\DateTimeZone::listIdentifiers(\DateTimeZone::AUSTRALIA) as $identifier) {
                $tz = new \DateTimeZone($identifier);
                $choiceName = (new \DateTime('now', $tz))->format('P');
                $choiceName .= ' ' . preg_replace('/_+/', ' ', explode('/', $identifier)[1]);
                $choices[$identifier] = $choiceName;
            }

            uasort($choices,function($a, $b) {
                return strcmp($a,$b);
            });

            return $choices;
        };

        $resolver->setDefaults(
            array(
                'required' => true,
                'label' => 'app.timezone.label',
                'placeholder' => 'app.timezone.choose',
                'choices' => $timezones(), /*array(
                    '+8:00' => '+8:00 Australian Western Standard Time',
                    '+9:30' => '+9:30 Australian Central Standard Time',
                    '+10:00' => '+10:00 Australian Eastern Standard Time',
                ),*/
            )
        );
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getName()
    {
        return 'app_timezone_selector';
    }

}
