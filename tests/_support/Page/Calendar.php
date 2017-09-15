<?php

namespace Page;

class Calendar
{

    public static $URL = '/calendar';

    public static $calendarContainer = "#calendar";
    public static $calendarSheet = "#calendar div.fc-view-container";

    public static $calendarPrevSheetButton = "button.app-calendar-prev";
    public static $calendarNextSheetButton = "button.app-calendar-next";
    public static $calendarViewRangeSelector = "select.app-calendar-view-range";
    public static $calendarTodaySheetButton = "button.app-calendar-today";

    public static function route($param)
    {
        return static::$URL . $param;
    }

}
