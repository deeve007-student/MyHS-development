<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 23:47
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\TreatmentNoteField;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class TreatmentNoteFieldListener
{

    use RecomputeChangesTrait;

    /** @var TokenStorage */
    protected $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if ($entity instanceof TreatmentNoteField) {
                if (!$entity->getTemplateField()) {

                    /** @var TreatmentNoteField $noteField */
                    foreach ($entity->getNoteFields() as $noteField) {
                        $noteField->setName($entity->getName());
                        $noteField->setPosition($entity->getPosition());
                        $this->computeEntityChangeSet($noteField, $em);
                    }

                }
            }

        }

    }

}
