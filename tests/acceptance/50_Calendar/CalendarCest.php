<?php

class CalendarCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function canSeeCalendarSheet(AcceptanceTester $I, \Page\Login $loginPage)
    {
        $I->wantTo('Check if calendar opens and view range actions works');
        $loginPage->loginAsUser();

        $I->amOnPage(\Page\Calendar::$URL);
        $I->waitForAjax();
        $I->seeElement(\Page\Calendar::$calendarContainer);
        $I->seeElement(\Page\Calendar::$calendarSheet);
        $I->seeElement(\Page\Calendar::$calendarViewRangeSelector);
        $I->seeElement(\Page\Calendar::$calendarNextSheetButton);
        $I->seeElement(\Page\Calendar::$calendarPrevSheetButton);
        $I->seeElement(\Page\Calendar::$calendarTodaySheetButton);

        // Todo: implement view range checks
    }

}
