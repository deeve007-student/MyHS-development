<?php

namespace Page;

class Dashboard
{
    public static $URL = '/dashboard';

    public static $dashboardTitleText = "dashboard";

    public static function route($param)
    {
        return static::$URL . $param;
    }

}
