<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 19:25
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\CalendarSettings;
use AppBundle\Entity\EventResource;
use AppBundle\Entity\UnavailableBlock;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use AppBundle\Form\Traits\EventTrait;
use AppBundle\Utils\EntityFactory;
use AppBundle\Utils\EventUtils;
use AppBundle\Utils\Formatter;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\VarDumper\VarDumper;

class UnavailableBlockType extends EventType
{
    use EventTrait;
    use AddFieldOptionsTrait;

    /** @var TokenStorage */
    protected $tokenStorage;

    /** @var EntityManager */
    protected $entityManager;

    /** @var EventResource */
    protected $resourceToMap = null;

    public function setTokenStorage($tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addEventSetListener($builder);
        $this->addEventBasicFields($builder, $this->eventUtils);
        $this->addEventSubmitListener($builder);

        $columns = array();
        /** @var CalendarSettings $calendarSettings */
        $calendarSettings = $this->tokenStorage->getToken()->getUser()->getCalendarSettings();
        $resources = $calendarSettings->getResources();
        $resourcesById = array();
        /** @var EventResource $resource */
        foreach ($resources as $resource) {
            $columns[$resource->getId()] = $resource->getName();
            $resourcesById[$resource->getId()] = $resource;
        }
        $columns['both'] = 'Both columns';

        $builder->add(
            'resource',
            ChoiceType::class,
            array(
                'mapped' => false,
                'choices' => $columns,
            )
        );

        $builder->add(
            'isMirror',
            CheckboxType::class,
            array(
                'required' => false,
            )
        );

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            if ($data instanceof UnavailableBlock) {
                if ($data->isMirror()) {
                    $this->addFieldOptions($event->getForm(), "resource", array('data' => 'both'));
                }
            }
        });

        $columnChosen = null;
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use (&$columnChosen, $resourcesById) {
            $data = $event->getData();
            if ($data['resource'] == 'both') {
                reset($resourcesById);
                $firstColumnId = key($resourcesById);
                $data['isMirror'] = true;
                $this->resourceToMap = $resourcesById[$firstColumnId];
            } else {
                $data['isMirror'] = false;
                $this->resourceToMap = $resourcesById[$data['resource']];
            }
            $event->setData($data);
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            if ($this->resourceToMap) {
                $data = $event->getData();
                $data->setResource($this->resourceToMap);
                $this->resourceToMap = null;
                $event->setData($data);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\UnavailableBlock',
            )
        );
    }

    public function getName()
    {
        return 'app_unavailable_block';
    }

}