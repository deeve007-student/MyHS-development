<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 02.07.2017
 * Time: 22:47
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\DocumentCategory;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoicePayment;
use AppBundle\Entity\InvoiceProduct;
use AppBundle\Entity\InvoiceRefund;
use AppBundle\Entity\InvoiceTreatment;
use AppBundle\Entity\Refund;
use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DocumentCategoryListener
{

    use RecomputeChangesTrait;

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof DocumentCategory) {
                $documents = $em->getRepository('AppBundle:Document')->findBy(array(
                    'category' => $entity,
                ));

                foreach ($documents as $document) {
                    $document->setCategory($em->getRepository('AppBundle:DocumentCategory')->findOneBy(array(
                        'defaultCategory' => true,
                    )));
                    $this->recomputeEntityChangeSet($document, $em);
                }
            }
        }
    }

}
