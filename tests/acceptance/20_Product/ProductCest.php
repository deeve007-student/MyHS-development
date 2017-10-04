<?php

class ProductCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function canCreateProduct(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if valid product can be persisted');
        $loginPage->loginAsUser();

        $I->amOnPage(\Page\Product::$URL);
        $I->waitForAjax();
        $I->seeElement(\Page\Product::$addProductButton);

        $I->click(\Page\Product::$addProductButton);
        $I->waitForAjax();
        $I->seeElement(\Page\Product::$productNameField);
        $I->seeElement(\Page\Product::$productPriceField);
        $I->fillField(\Page\Product::$productNameField,'Some new product');
        $I->fillField(\Page\Product::$productPriceField,'25');
        $I->seeInCurrentUrl(\Page\Product::$newProductURL);

        $I->click(\Page\Product::$productFormSaveButton);
        $I->waitForAjax();
        $I->dontSee('This value should not be blank');
        $I->dontSeeInCurrentUrl(\Page\Product::$newProductURL);
        $I->seeInCurrentUrl(\Page\Product::$URL);

        $I->makeScreenshot();
    }

    public function cannotCreateInvalidProduct(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if invalid product can not be persisted');
        $loginPage->loginAsUser();

        $I->amOnPage(\Page\Product::$newProductURL);
        $I->waitForAjax();

        $I->click(\Page\Product::$productFormSaveButton);
        $I->waitForAjax();
        $I->see('This value should not be blank');
        $I->seeInCurrentUrl(\Page\Product::$newProductURL);

        $I->makeScreenshot();
    }

}
