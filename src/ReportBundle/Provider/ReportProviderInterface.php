<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 14:37
 */

namespace ReportBundle\Provider;

interface ReportProviderInterface
{

    /**
     * @param $reportFormData
     * @return Node
     */
    function getReportData($reportFormData);

}
