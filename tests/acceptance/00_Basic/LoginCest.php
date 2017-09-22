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
        $I->dontSee('exception');
        $loginPage->loginPageLooksCorrect();
    }

    public function loginPracticionerWorks(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if login as practicioner works');
        $loginPage->loginAsUser();

        $I->seeInCurrentUrl(\Page\Dashboard::$URL);
    }

    public function loginAdminWorks(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if login as admin works');
        $loginPage->loginAsAdmin();

        $I->seeInCurrentUrl(\Page\Dashboard::$URL);
    }

    public function logoutWorks(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if logout by direct URL works');
        $loginPage->loginAsAdmin();

        $I->amOnPage(\Page\Login::$logoutURL);

        $loginPage->loginPageLooksCorrect();
    }

}
