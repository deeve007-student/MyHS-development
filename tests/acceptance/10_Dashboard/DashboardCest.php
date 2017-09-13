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
        $loginPage->loginAsUser();

        $I->amOnPage(\Page\Dashboard::$URL);
        $I->seeElement(\Page\UserMenu::$userMenu);
    }

    public function canSeeAllWidgets(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if all the widgets exists on dashboard');
        $loginPage->loginAsUser();

        $I->amOnPage('/dashboard');
        $I->waitForAjax();
        $I->seeElement(\Page\Dashboard::$widgetCalendar);
        $I->seeElement(\Page\Dashboard::$widgetInvoice);
        $I->seeElement(\Page\Dashboard::$widgetRecall);
        $I->seeElement(\Page\Dashboard::$widgetTask);
        $I->seeElement(\Page\Dashboard::$widgetTreatmentNote);
        $I->seeElement(\Page\Dashboard::$widgetCommunication);
    }

}
