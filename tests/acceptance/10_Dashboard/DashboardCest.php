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

    public function canSeeMainMenuLinks(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if links in main menu exists');
        $loginPage->loginAsUser();

        $I->amOnPage(\Page\Dashboard::$URL);
        $I->seeElement(\Page\MainMenu::$dashboardLink);
        $I->seeElement(\Page\MainMenu::$calendarLink);
        $I->seeElement(\Page\MainMenu::$patientsLink);
        $I->seeElement(\Page\MainMenu::$invoicesLink);
        $I->seeElement(\Page\MainMenu::$treatmentsLink);
        $I->seeElement(\Page\MainMenu::$productsLink);
        $I->seeElement(\Page\MainMenu::$communicationsLink);
        $I->seeElement(\Page\MainMenu::$reportsLink);
    }

    public function canSeeAllWidgets(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if all the widgets exists on dashboard');
        $loginPage->loginAsUser();

        $I->amOnPage(\Page\Dashboard::$URL);
        $I->waitForAjax();
        $I->seeElement(\Page\Dashboard::$widgetCalendar);
        $I->seeElement(\Page\Dashboard::$widgetInvoice);
        $I->seeElement(\Page\Dashboard::$widgetRecall);
        $I->seeElement(\Page\Dashboard::$widgetTask);
        $I->seeElement(\Page\Dashboard::$widgetTreatmentNote);
        $I->seeElement(\Page\Dashboard::$widgetCommunication);
    }

}
