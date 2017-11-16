<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 16.11.17
 * Time: 19:32
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Attachment;
use AppBundle\Entity\InvoiceLogo;
use AppBundle\Entity\Patient;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\VarDumper\VarDumper;

/**
 * InvoiceLogo controller.
 */
class InvoiceLogoController extends Controller
{


    /**
     * Open attachment.
     *
     * @Route("/settings/invoice-logo/{id}/open", name="invoice_logo_open")
     * @Method("GET")
     */
    public function openAction(InvoiceLogo $attachment)
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
     * Creates invoice logo.
     *
     * @Route("/settings/invoice-logo/new", name="invoice_logo_create")
     * @Method({"GET", "POST"})
     * @Template("@App/InvoiceLogo/update.html.twig")
     */
    public function createAction()
    {
        $attachment = new InvoiceLogo();

        $this->getUser()->getInvoiceSettings()->setLogoAttachment($attachment);

        if ($attachment->getFile() && !$attachment->isImage()) {
            $this->addFlash('danger', 'Its not an image!');
        }

        $result = $this->update($attachment);

        return $result;
    }

    /**
     * Deletes a attachment entity.
     *
     * @Route("/settings/invoice-logo/delete", name="invoice_logo_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request)
    {
        $this->getDoctrine()->getManager()->remove($this->getUser()->getInvoiceSettings()->getLogoAttachment());
        $this->getUser()->getInvoiceSettings()->setLogoAttachment(null);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'success',
            'app.invoice_logo.message.deleted'
        );

        return $this->redirectToRoute('practicioner_settings_index');
    }

    protected function update($entity)
    {

        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.invoice_logo.form'),
            null,
            $entity,
            'app.invoice_logo.message.created',
            'app.invoice_logo.message.updated',
            'practicioner_settings_index'
        );
    }
}
