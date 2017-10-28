<?php

namespace ReportBundle\Controller;

use ReportBundle\Entity\Node;
use ReportBundle\Form\Type\DateRangeType;
use ReportBundle\Formatter\XlsFormatterInterface;
use ReportBundle\Provider\ReportProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ReportController controller.
 */
class ReportController extends Controller
{

    /**
     * @Route("/report", name="report_index")
     * @Template
     */
    public function indexAction(Request $request)
    {
        return array();
    }

    /**
     * @Route("/report/appointments", name="report_appointments")
     * @Template
     */
    public function appointmentsAction(Request $request)
    {
        $form = $this->get('app.report_appointments.form');

        $data = $this->processReportForm(
            $form,
            $request,
            $this->get('app.report_provider.appointments'),
            $this->get('app.xls_formatter.appointments'),
            array(
                'range' => DateRangeType::CHOICE_ALL,
            ),
            true
        );

        return array(
            'form' => $form->createView(),
            'data' => $data,
        );
    }

    /**
     * @Route("/report/patients", name="report_patients")
     * @Template
     */
    public function patientsAction(Request $request)
    {
        $form = $this->get('app.report_patients.form');

        $data = $this->processReportForm(
            $form,
            $request,
            $this->get('app.report_provider.patients'),
            $this->get('app.xls_formatter.patients'),
            array(
                'range' => DateRangeType::CHOICE_ALL,
            ),
            true
        );

        return array(
            'form' => $form->createView(),
            'data' => $data,
        );
    }

    /**
     * @Route("/report/invoices", name="report_invoices")
     * @Template
     */
    public function invoicesAction(Request $request)
    {
        $form = $this->get('app.report_invoices.form');

        $data = $this->processReportForm(
            $form,
            $request,
            $this->get('app.report_provider.invoices'),
            $this->get('app.xls_formatter.invoices'),
            array(
                'range' => DateRangeType::CHOICE_ALL,
            ),
            true
        );

        return array(
            'form' => $form->createView(),
            'data' => $data,
        );
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @param ReportProviderInterface $reportProvider
     * @param XlsFormatterInterface $xlsFormatter
     * @param $defaultFormData
     * @param bool $startWithEmptyResults
     * @return Node
     */
    protected function processReportForm(
        FormInterface $form,
        Request $request,
        ReportProviderInterface $reportProvider,
        XlsFormatterInterface $xlsFormatter = null,
        $defaultFormData = null,
        $startWithEmptyResults = false
    )
    {
        $form->handleRequest($request);

        $data = null;

        if (!$form->getData() && $defaultFormData) {
            $form->setData($defaultFormData);
        }

        if (($startWithEmptyResults && $form->isSubmitted()) || !$startWithEmptyResults) {
            $data = $reportProvider->getReportData($form->getData());
            if ($xlsFormatter) {
                $this->handleXlsRequest($form, $xlsFormatter, $data, $form->getData());
            }
        }

        return $data;
    }

    protected function handleXlsRequest(FormInterface $form, XlsFormatterInterface $xlsFormatter, Node $data, $formData)
    {
        if ($form->has('xls') && $form->get('xls')->getData() == '1') {

            $excel = $xlsFormatter->getXls($data, $formData);

            $fileName = uniqid($form->getName() . '_');

            if ($xlsFormatter::xlsFileName) {
                $fileName = $xlsFormatter::xlsFileName;
            }

            $response = new Response();
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '.xls"');
            $response->headers->set('Cache-Control', 'max-age=0');
            $response->sendHeaders();

            $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
            $objWriter->save('php://output');
        }
    }
}
