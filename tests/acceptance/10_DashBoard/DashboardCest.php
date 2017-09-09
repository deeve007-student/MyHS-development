<?php

class DashboardCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function canSeeUserMenu(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if user menu available');

        $loginPage->login('stepan@yudin.com', '123123123123');

        $I->amOnPage(\Page\Dashboard::$URL);
        $I->seeElement(\Page\UserMenu::$userMenu);
    }

    public function canLogoutWithUserMenu(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if user can logout with user menu');

        $loginPage->login('stepan@yudin.com', '123123123123');

        $I->amOnPage('/dashboard');
        $I->waitForAjax();
        $I->click(\Page\UserMenu::$userMenu);
        $I->seeElement(\Page\UserMenu::$logoutLink);
        $I->click(\Page\UserMenu::$logoutLink);

        $loginPage->loginPageLooksCorrect();
    }

}
