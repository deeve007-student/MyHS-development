<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 31.03.2017
 * Time: 16:16
 */

namespace AppBundle\Form\Traits;

use AppBundle\Entity\ConcessionPrice;
use AppBundle\Entity\ConcessionPriceOwner;
use AppBundle\Form\Type\ConcessionPricesType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\VarDumper\VarDumper;

trait ConcessionPricesTrait
{

    /**
     * @param FormBuilderInterface $builder
     */
    protected function addConcessionPricesField(FormBuilderInterface $builder, EntityManager $entityManager)
    {

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($entityManager) {
                $form = $event->getForm();
                $entity = $event->getData();
                if ($entity instanceof ConcessionPriceOwner) {

                    if ($entity->getConcessionPrices()->count() == 0) {
                        foreach ($entityManager->getRepository('AppBundle:Concession')->findAll() as $concession) {
                            $concessionPrice = new ConcessionPrice();
                            $concessionPrice->setConcession($concession)
                                ->setPrice(0);

                            // Todo: Investigate why do we need to manually set relations on the both sides?
                            $concessionPrice->setConcessionPriceOwner($entity);
                            $entity->addConcessionPrice($concessionPrice);
                        }
                    }

                    $form->add(
                        'concessionPrices',
                        ConcessionPricesType::class
                    );

                }
            }
        );

    }

}
