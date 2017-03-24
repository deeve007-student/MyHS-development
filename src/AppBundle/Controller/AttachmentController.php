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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Attachment controller.
 *
 * @Route("attachment")
 */
class AttachmentController extends Controller
{

    /**
     * Download attachment.
     *
     * @Route("/{id}/download", name="attachment_download")
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
     * @Route("/{id}/open", name="attachment_open")
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
     * Lists all attachment entities.
     *
     * @Route("/", name="attachment_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('AppBundle:Attachment')
            ->createQueryBuilder('a')
            ->getQuery();

        $paginator = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            self::ITEMS_PER_PAGE
        );

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new attachment entity.
     *
     * @Route("/new", name="attachment_create")
     * @Method({"GET", "POST"})
     * @Template("@App/Attachment/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $attachment = $this->get('app.entity_factory')->createAttachment();

        return $this->update($attachment);
    }

    /**
     * Creates a new attachment entity.
     *
     * @Route("/new/{id}", name="attachment_create_from_patient")
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
     * Displays a form to edit an existing attachment entity.
     *
     * @Route("/{id}/update", name="attachment_update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Attachment $attachment)
    {
        return $this->update($attachment);
    }

    /**
     * Deletes a attachment entity.
     *
     * @Route("/{id}/delete", name="attachment_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Attachment $attachment)
    {
        $patient = $this->getPatientByAttachment($attachment);

        $patient->removeAttachment($attachment);
        $em = $this->getDoctrine()->getManager();
        //$em->remove($attachment);
        $em->flush();

        $this->addFlash(
            'success',
            'app.attachment.message.deleted'
        );

        return $this->redirectToRoute(
            'patient_attachment_index',
            array(
                'id' => $patient->getId(),
            )
        );
    }

    protected function update($entity)
    {

        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.attachment.form'),
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
                        'id' => $patient->getId(),
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
