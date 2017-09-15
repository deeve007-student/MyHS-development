<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 11.09.2017
 * Time: 17:39
 */

namespace Page;

class Start
{

    public static $URL = '/start';

    public static $agreeCheckbox = '#app_user_registration_confirmed_agree';
    public static $submitButton = 'form[name="app_user_registration_confirmed"] input[type="submit"]:last-child';

    public static function route($param)
    {
        return static::$URL . $param;
    }

}
