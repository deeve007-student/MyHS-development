<?php

namespace Page;

class Login
{

    /**
     * @var \AcceptanceTester
     */
    protected $tester;

    public static $URL = '/login';
    public static $logoutURL = '/logout';

    public static $loginSession = 'login';

    public static $usernameField = '#username';
    public static $passwordField = '#password';
    public static $loginButton = "#app-login-form button:last-child";

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
    }

    public function login($name, $password)
    {
        $I = $this->tester;

        if ($I->loadSessionSnapshot($this->getSessionName())) {
            return $this;
        }

        $I->amOnPage(self::$URL);
        $I->fillField(self::$usernameField, $name);
        $I->fillField(self::$passwordField, $password);
        $I->click(self::$loginButton);

        return $this;
    }

    public function saveLoginSession()
    {
        $I = $this->tester;

        $I->saveSessionSnapshot($this->getSessionName());

        return $this;
    }

    public function loginPageLooksCorrect()
    {
        $I = $this->tester;

        $I->seeElement(self::$usernameField);
        $I->seeElement(self::$passwordField);
        $I->seeElement(self::$loginButton);

        return $this;
    }

    protected function getCalledCest()
    {
        $trace = debug_backtrace();
        if (isset($trace[3])) {
            return $trace[3]['class'];
        }

        throw new \Exception('Cannot determine called class');
    }

    protected function getSessionName()
    {
        return $this->getCalledCest() . '_' . self::$loginSession;
    }

}
