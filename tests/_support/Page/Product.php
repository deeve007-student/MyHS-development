<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 11.09.2017
 * Time: 17:39
 */

namespace Page;

class Product
{

    public static $URL = '/product';
    public static $newProductURL = '/product/new';

    public static $addProductButton = 'a[href="/product/new"]';

    public static $productForm = 'form[name="app_product"]';
    public static $productNameField = 'form[name="app_product"] input[id="app_product_name"]';
    public static $productPriceField = 'form[name="app_product"] input[id="app_product_price"]';
    public static $productFormSaveButton = 'form[name="app_product"] button[type="submit"]';

    public static function route($param)
    {
        return static::$URL . $param;
    }

}
