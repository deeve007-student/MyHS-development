<?php

namespace Page;

class Dashboard
{
    public static $URL = '/dashboard';

    public static $dashboardTitleText = "dashboard";

    public static $widgetCalendar = "div.appointment-list";
    public static $widgetTreatmentNote = "div#treatment-notes-widget-contents";
    public static $widgetInvoice = "div#invoice-widget-contents";
    public static $widgetRecall = "div#recall-widget-contents";
    public static $widgetTask = "div#task-widget-contents";

    public static function route($param)
    {
        return static::$URL . $param;
    }

}
