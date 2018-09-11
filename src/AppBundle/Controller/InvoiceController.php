<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\AppointmentPatient;
use AppBundle\Entity\Event;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoiceTreatment;
use AppBundle\Entity\Message;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Refund;
use AppBundle\Entity\Treatment;
use AppBundle\Utils\AppNotificator;
use AppBundle\Utils\FilterUtils;
use AppBundle\Utils\Templater;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Invoice controller.
 *
 * Route("invoice")
 */
class InvoiceController extends Controller
{

    /**
     * Lists all invoice entities.
     *
     * @Route("/invoice/", name="invoice_index", options={"expose"=true})
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->getRepository('AppBundle:Invoice')->createQueryBuilder('i');
        $qb->leftJoin('i.patient', 'p')
            ->orderBy('i.date', 'DESC');

        $qbr = $em->getRepository('AppBundle:Refund')->createQueryBuilder('r');
        $qbr->where('r.invoice IS NULL')
            ->orderBy('r.createdAt', 'DESC');

        $result = $this->filterInvoices($request, array($qb, $qbr));

        return $result;
    }

    /**
     * Lists all patients invoices.
     *
     * @Route("/patient/{id}/invoice", name="patient_invoice_index")
     * @Method({"GET","POST"})
     * @Template("@App/Invoice/indexPatient.html.twig")
     */
    public function indexPatientAction(Request $request, Patient $patient)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:Invoice')
            ->createQueryBuilder('i')
            ->where('i.patient = :patient')
            ->setParameter('patient', $patient)
            ->leftJoin('i.patient', 'p')
            ->orderBy('i.date', 'DESC');

        $result = $this->filterInvoices($request, $qb);

        if (is_array($result)) {
            $result['entity'] = $patient;
        }

        return $result;
    }

    protected function filterInvoices(Request $request, $qb)
    {
        $result = $this->get('app.datagrid_utils')->handleDatagrid(
            $this->get('app.invoice_filter.form'),
            $request,
            $qb,
            function ($qb, $filterData) {

                $invoiceFilter = function (QueryBuilder &$qb, $filterData) {
                    FilterUtils::buildTextGreedyCondition(
                        $qb,
                        array(
                            'name',
                            'p.title',
                            'p.firstName',
                            'p.lastName',
                            'p.email',
                            'p.mobilePhone',
                        ),
                        $filterData['string']
                    );

                    if ($filterData['status']) {
                        $qb->andWhere($qb->expr()->in('i.status', ':filterStatuses'))
                            ->setParameter('filterStatuses', $filterData['status']);
                    }
                };

                if (is_array($qb)) {
                    /** @var QueryBuilder $builder */
                    foreach ($qb as $builder) {
                        if ($builder->getRootEntities()[0] == Invoice::class) {
                            $invoiceFilter($builder, $filterData);
                        }
                        if ($builder->getRootEntities()[0] == Refund::class) {
                            if (trim($filterData['string']) !== '' || $filterData['status']) {
                                $builder->andWhere('r.id IS NULL');
                            }
                        }
                    }
                } else {
                    $invoiceFilter($qb, $filterData);
                }
            },
            '@App/Invoice/include/grid.html.twig',
            null,
            function (&$resultArray) {
                usort($resultArray, function ($a, $b) {
                    if ($a instanceof Invoice) {
                        $ad = $a->getDate();
                    }
                    if ($b instanceof Invoice) {
                        $bd = $b->getDate();
                    }
                    if ($a instanceof Refund) {
                        $ad = $a->getCreatedAt();
                    }
                    if ($b instanceof Refund) {
                        $bd = $b->getCreatedAt();
                    }
                    return $ad > $bd ? -1 : 1;
                });
            }
        );

        return $result;
    }

    /**
     * Creates a new invoice entity.
     *
     * @Route("/invoice/new", name="invoice_create")
     * @Method({"GET", "POST"})
     * @Template("@App/Invoice/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $invoice = $this->get('app.entity_factory')->createInvoice();

        return $this->update($invoice);
    }

    /**
     * Creates a new patient invoice entity.
     *
     * @Route("/patient/{patient}/invoice/new", name="patient_invoice_create")
     * @Method({"GET", "POST"})
     * @Template("@App/Invoice/update.html.twig")
     */
    public function createFromPatientAction(Patient $patient)
    {
        $invoice = $this->get('app.entity_factory')->createInvoice($patient);

        $result = $this->update($invoice);

        if (is_array($result)) {
            $result['backToPatient'] = true;
        }

        return $result;
    }

    /**
     * Creates a new patient invoice entity from appointment.
     *
     * @Route("/patient/{patient}/invoice/new/appointment/{appointmentPatient}", name="appointment_invoice_create")
     * @Method({"GET", "POST"})
     * @Template("@App/Invoice/update.html.twig")
     */
    public function createFromAppointmentAction(Patient $patient, AppointmentPatient $appointmentPatient)
    {
        $invoice = $this->get('app.entity_factory')->createInvoice($patient);

        $invoiceTreatment = new InvoiceTreatment();

        $treatmentToAdd = $appointmentPatient->getAppointment()->getTreatment();
        if ($appointmentPatient->isNoShow()) {
            $treatmentToAdd = $this->getDoctrine()->getManager()->getRepository(Treatment::class)
                ->findOneBy([
                    'noShowFee' => true,
                ]);
        }

        $invoiceTreatment->setTreatment($treatmentToAdd);
        $invoiceTreatment->setPrice($treatmentToAdd->getPrice());
        $invoiceTreatment->setQuantity(1);

        $invoice->addInvoiceTreatment($invoiceTreatment);
        $invoice->setDate($appointmentPatient->getAppointment()->getStart());
        $invoice->addAppointmentPatient($appointmentPatient);

        $result = $this->update($invoice);

        if (is_array($result)) {
            $result['backToPatient'] = true;
            $result['additionalActions'] = true;
        }

        $form = $this->get('app.invoice.form');
        if ($form->isSubmitted() && $form->isValid()) {

            $nextAction = $form->get('nextAction')->getData();
            switch ($nextAction) {
                case 'save_and_return_to_cal':
                    return $this->redirectToRoute('calendar_index');
                    break;
                case 'save_and_book_again':
                    return $this->redirectToRoute('calendar_event_book_again', array(
                        'event' => $this->get('app.hasher')->encodeObject($invoice->getAppointmentPatients()->last(), Event::class),
                    ));
                    break;
                case 'save_and_recall':
                    return $this->redirectToRoute('patient_recall_index_with_new', array(
                        'id' => $this->get('app.hasher')->encodeObject($invoice->getPatient()),
                    ));
                    break;
                default:
                    break;
            }

        }

        return $result;
    }

    /**
     * Finds and displays a invoice entity.
     *
     * @Route("/invoice/{id}", name="invoice_view", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function viewAction(Invoice $invoice)
    {
        // Todo: move this to listener. Now it doesn't work with new invoice
        $this->get('app.invoice_status_listener')->recalculateInvoiceStatus($invoice, $this->getDoctrine()->getManager());
        $this->getDoctrine()->getManager()->flush();

        return array(
            'entity' => $invoice,
        );
    }

    /**
     * Changes invoice status.
     *
     * @Route("/invoice/{id}/status/{status}", name="invoice_status_update")
     * @Method("GET")
     */
    public function statusAction(Invoice $invoice, $status)
    {
        if ($this->get('app.entity_factory')->updateInvoiceStatus($invoice, $status)) {
            $this->addFlash(
                'success',
                'app.invoice.message.status_changed'
            );
        } else {
            $this->addFlash(
                'danger',
                'app.invoice.message.status_no_items'
            );
        }

        return $this->redirectToRoute('invoice_view', array('id' => $this->get('app.hasher')->encodeObject($invoice)));
    }

    /**
     * Duplicates invoice.
     *
     * @Route("/invoice/{id}/duplicate", name="invoice_duplicate")
     * @Method("GET")
     */
    public function duplicateAction(Invoice $invoice)
    {
        $duplicate = $this->get('app.entity_factory')->duplicateInvoice($invoice);

        $this->addFlash(
            'success',
            'app.invoice.message.duplicated'
        );

        return $this->redirectToRoute(
            'invoice_view',
            array('id' => $this->get('app.hasher')->encodeObject($duplicate))
        );
    }

    protected function updateStatus(Invoice $invoice)
    {
        if ($invoice->getStatus() == Invoice::STATUS_DRAFT) {
            $invoice->setStatus(Invoice::STATUS_PENDING);
        }
    }

    /**
     * Displays a form to edit an existing invoice entity.
     *
     * @Route("/invoice/{id}/update", name="invoice_update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Invoice $invoice)
    {
        $this->updateStatus($invoice);
        return $this->update($invoice);
    }

    /**
     * Edit invoice entity from appointment ("pay invoice" button).
     *
     * @Route("/invoice/{invoice}/update-from-appointment/{appointment}", name="invoice_update_from_appointment")
     * @Method({"GET", "POST"})
     * @Template("@App/Invoice/update.html.twig")
     */
    public function updateFromAppointmentAction(Invoice $invoice, Appointment $appointment)
    {
        $this->get('app.entity_factory')->copyPrevInvoicesItems($invoice, true);

        $this->updateStatus($invoice);
        $result = $this->update($invoice);

        if (is_array($result)) {
            $result['backToPatient'] = true;
            $result['additionalActions'] = true;
        }

        $form = $this->get('app.invoice.form');
        if ($form->isSubmitted() && $form->isValid()) {

            $nextAction = $form->get('nextAction')->getData();
            switch ($nextAction) {
                case 'save_and_return_to_cal':
                    return $this->redirectToRoute('calendar_index');
                    break;
                case 'save_and_book_again':
                    return $this->redirectToRoute('calendar_event_book_again', array(
                        'event' => $this->get('app.hasher')->encodeObject($invoice->getAppointments()->last(), Event::class),
                    ));
                    break;
                case 'save_and_recall':
                    return $this->redirectToRoute('patient_recall_index_with_new', array(
                        'id' => $this->get('app.hasher')->encodeObject($invoice->getPatient()),
                    ));
                    break;
                default:
                    break;
            }

        }

        return $result;
    }

    /**
     * Deletes a invoice entity.
     *
     * @Route("/invoice/{id}/delete", name="invoice_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Invoice $invoice)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($invoice);
        $em->flush();

        $this->addFlash(
            'success',
            'app.invoice.message.deleted'
        );

        return $this->redirectToRoute('invoice_index');
    }

    /**
     * Sends invoice PDF to client.
     *
     * @Route("/invoice/{id}/pdf-send", name="invoice_pdf_send", options={"expose"=true})
     * @Method({"GET","POST"})
     */
    public function sendPdfAction(Request $request, Invoice $invoice)
    {
        $this->checkInvoiceNotDraft($invoice);

        /** @var AppNotificator $notificator */
        $notificator = $this->get('app.notificator');

        /** @var Templater $templater */
        $templater = $this->get('app.templater');

        $patient = $invoice->getPatient();
        $tempInvoice = $this->generateInvoiceTempFile($invoice);

        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                '@App/Invoice/pdf.html.twig',
                array(
                    'entity' => $invoice,
                )
            ),
            $tempInvoice
        );

        $message = new Message();
        $message->setRecipient($patient)
            ->setSubject('Invoice ' . $invoice . ' issued at ' . $this->get('app.formatter')->formatDate($invoice->getDate()))
            ->setBodyData(array(
                    'template' => '@App/Invoice/email.html.twig',
                    'data' => array(
                        'invoiceEmailBody' => $templater->compile($invoice->getOwner()->getInvoiceSettings()->getInvoiceEmail(), array(
                            'entity' => $invoice,
                        )),
                    )
                )
            )->setRouteData(array(
                'route' => 'invoice_view',
                'parameters' => array(
                    'id' => $this->get('app.hasher')->encodeObject($invoice),
                ),
            ))->setTag(Message::TAG_INVOICE_SENT)
            ->setOwner($patient->getOwner());

        $message->addAttachment($tempInvoice);

        if ($notificator->sendMessage($message)) {
            $result = array(
                'error' => 0,
                'message' => 'app.invoice.message.email_pdf_sent',
            );
        } else {
            $result = array(
                'error' => 1,
                'message' => 'app.invoice.message.email_pdf_blank_email',
            );
        }

        /*
        if ($patient->getEmail()) {
            $body = $this->renderView(
                '@App/Invoice/email.html.twig',
                array(
                    'patient' => $patient,
                )
            );

            $message = $mailer->createPracticionerMessage($patient->getOwner())
                ->setSubject(
                    'Invoice ' . $invoice . ' issued at ' . $this->get('app.formatter')->formatDate($invoice->getDate())
                )
                ->setTo($patient->getEmail(), (string)$patient)
                ->setBody($body);

            $message->attach(\Swift_Attachment::fromPath($tempInvoice));
            $mailer->send($message, true);

            $messageLog = new MessageLog();
            $messageLog->setType(Message::TYPE_EMAIL);
            $messageLog->setTag(MessageLog::TAG_INVOICE_SENT);
            $messageLog->setPatient($invoice->getPatient());
            $messageLog->setRouteData(array(
                'route' => 'invoice_view',
                'parameters' => array(
                    'id' => $this->get('app.hasher')->encodeObject($invoice),
                ),
            ));

            $this->getDoctrine()->getManager()->persist($messageLog);
            $this->getDoctrine()->getManager()->flush();

            $result = array(
                'error' => 0,
                'message' => 'app.invoice.message.email_pdf_sent',
            );
        } else {
            $result = array(
                'error' => 1,
                'message' => 'app.invoice.message.email_pdf_blank_email',
            );
        }
        */

        unlink($tempInvoice);

        return new JsonResponse(json_encode($result));
    }

    /**
     * Invoice PDF.
     *
     * @Route("/invoice/{id}/pdf", name="invoice_pdf")
     * @Template("@App/Invoice/pdf.html.twig")
     * @Method({"GET"})
     */
    public function openPdfAction(Request $request, Invoice $invoice)
    {
        $this->checkInvoiceNotDraft($invoice);

        $html = $this->renderView(
            '@App/Invoice/pdf.html.twig',
            array(
                'entity' => $invoice,
            )
        );

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'filename="' . $this->generateInvoiceFileName($invoice) . '"',
            )
        );
    }

    protected function checkInvoiceNotDraft(Invoice $invoice)
    {
        if ($invoice->isDraft()) {
            throw new \Exception('Available only for non-draft invoices');
        }
    }

    protected function generateInvoiceFileName(Invoice $invoice)
    {
        return uniqid('invoice_' . $invoice . '_') . '.pdf';
    }

    protected function generateInvoiceTempFile(Invoice $invoice)
    {
        return $this->getParameter('kernel.root_dir') . '/../temp/' . $this->generateInvoiceFileName($invoice);
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.invoice.form'),
            null,
            $entity,
            'app.invoice.message.created',
            'app.invoice.message.updated',
            'invoice_view',
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

}
