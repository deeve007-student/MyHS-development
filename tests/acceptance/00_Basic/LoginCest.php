<?php

class LoginCest
{

    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function loginPageOpens(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if login page opens and contains form');

        $I->amOnPage(\Page\Login::$URL);
        $loginPage->loginPageLooksCorrect();
    }

    public function loginWorks(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if login as practicioner works');

        $loginPage->login('stepan@yudin.com', '123123123123');
        $I->seeInCurrentUrl(\Page\Dashboard::$URL);

        $loginPage->saveLoginSession();
    }

    public function logoutWorks(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if logout by direct URL works');

        $loginPage->login('stepan@yudin.com', '123123123123');

        $I->amOnPage(\Page\Login::$logoutURL);

        $loginPage->loginPageLooksCorrect();
    }

}
