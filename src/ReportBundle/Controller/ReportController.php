<?php

namespace ReportBundle\Controller;

use AppBundle\Utils\DateTimeUtils;
use ReportBundle\Entity\Node;
use ReportBundle\Form\Type\DateRangeType;
use ReportBundle\Formatter\XlsFormatterInterface;
use ReportBundle\Provider\ReportProviderInterface;
use AppBundle\Utils\DateRangeUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
            null, //$this->get('app.xls_formatter.income'),
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
            null, //$this->get('app.xls_formatter.income'),
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
            null, //$this->get('app.xls_formatter.income'),
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
                $this->handleXlsRequest($form, $xlsFormatter, $data);
            }
        }

        return $data;
    }

    protected function handleXlsRequest(FormInterface $form, XlsFormatterInterface $xlsFormatter, Node $data)
    {
        if ($form->has('xls') && $form->get('xls')->getData() == '1') {

            $excel = $xlsFormatter->getXls($data);

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
