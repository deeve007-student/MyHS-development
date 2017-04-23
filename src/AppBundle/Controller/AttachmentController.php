<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 17.03.2017
 * Time: 14:29
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Attachment;
use AppBundle\Entity\Patient;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Attachment controller.
 *
 * Route("attachment")
 */
class AttachmentController extends Controller
{


    /**
     * Lists all patients attachments.
     *
     * @Route("/patient/{id}/attachment", name="patient_attachment_index")
     * @Method({"GET","POST"})
     * @Template("@App/Attachment/indexPatient.html.twig")
     */
    public function indexAttachmentAction(Request $request, Patient $patient)
    {
        $attachments = $patient->getAttachments();

        $attachmentsIds = array();
        foreach ($attachments as $attachment) {
            $attachmentsIds[] = $attachment->getId();
        }

        $em = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->getRepository('AppBundle:Attachment')
            ->createQueryBuilder('a');

        $qb->where($qb->expr()->in('a.id', ':ids'))
            ->setParameter('ids', $attachmentsIds);

        $result = $this->get('app.datagrid_utils')->handleDatagrid(
            null,
            $request,
            $qb,
            null,
            '@App/Attachment/include/grid.html.twig'
        );

        if (is_array($result)) {
            $result['entity'] = $patient;
        }

        return $result;
    }

    /**
     * Download attachment.
     *
     * @Route("/attachment/{id}/download", name="attachment_download")
     * @Method("GET")
     */
    public function downloadAction(Attachment $attachment)
    {
        $downloadHandler = $this->get('vich_uploader.download_handler');

        return $downloadHandler->downloadObject($attachment, 'file', null, $attachment->getFileName());
    }

    /**
     * Open attachment.
     *
     * @Route("/attachment/{id}/open", name="attachment_open")
     * @Method("GET")
     */
    public function openAction(Attachment $attachment)
    {
        $file = $attachment->getFile();

        $response = new BinaryFileResponse($file);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $attachment->getFile()->getFilename()
        );

        $response->headers->set('Content-Type', $file->getMimeType());
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Length', $file->getSize());

        return $response;
    }

    /**
     * Creates a new attachment entity.
     *
     * @Route("/patient/{id}/attachment/new", name="attachment_create_from_patient")
     * @Method({"GET", "POST"})
     * @Template("@App/Attachment/update.html.twig")
     */
    public function createFromPatientAction(Patient $patient)
    {
        $attachment = $this->get('app.entity_factory')->createAttachment($patient);

        $result = $this->update($attachment);
        if (is_array($result)) {
            $result['patient'] = $patient;
        }

        return $result;
    }

    /**
     * Deletes a attachment entity.
     *
     * @Route("/attachment/{id}/delete", name="attachment_delete")
     * @Method({"DELETE", "GET"})
     *
     * @ParamConverter("patient",class="AppBundle:Patient")
     * @ParamConverter("attachment",class="AppBundle:Attachment")
     */
    public function deleteAction(Request $request, Attachment $attachment)
    {
        $patient = $this->getPatientByAttachment($attachment);

        $patient->removeAttachment($attachment);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash(
            'success',
            'app.attachment.message.deleted'
        );

        return $this->redirectToRoute(
            'patient_attachment_index',
            array(
                'id' => $this->get('app.hasher')->encodeObject($patient),
            )
        );
    }

    protected function update($entity)
    {

        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.attachment.form'),
            null,
            $entity,
            'app.attachment.message.created',
            'app.attachment.message.updated',
            null,
            null,
            function ($attachment) {
                /** @var QueryBuilder $qb */
                $patient = $this->getPatientByAttachment($attachment);

                return $this->redirectToRoute(
                    'patient_attachment_index',
                    array(
                        'id' => $this->get('app.hasher')->encodeObject($patient),
                    )
                );
            }
        );
    }

    /**
     * @param Attachment $attachment
     * @return Patient|null
     */
    protected function getPatientByAttachment(Attachment $attachment)
    {
        $qb = $this->getDoctrine()->getManager()->getRepository('AppBundle:Patient')->createQueryBuilder('p');

        return $qb->leftJoin('p.attachments', 'a')
            ->where('a.id = :attachmentId')
            ->setParameter('attachmentId', $attachment->getId())
            ->getQuery()->getOneOrNullResult();
    }
}
