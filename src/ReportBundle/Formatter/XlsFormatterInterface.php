<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.06.2017
 * Time: 14:36
 */

namespace ReportBundle\Formatter;

interface XlsFormatterInterface
{
    function getXls($node);
}
