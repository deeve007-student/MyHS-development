<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * Define custom actions here
     */

    const ADMIN_LOGIN='stepan@yudin.com';
    const ADMIN_PASSWORD='123123123123';

    const USER_LOGIN='david@rooney.com';
    const USER_PASSWORD='123123123123';

    public function waitForAjax()
    {
        //$jsStartCondition = 'return $.active !== 0;';
        //$this->waitForJs($jsStartCondition, 5);

        $jsFinishCondition = 'return $.active == 0;';
        $this->waitForJs($jsFinishCondition, 30);
    }
}
