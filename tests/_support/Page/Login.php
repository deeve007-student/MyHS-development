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

    public static $usernameField = '#username';
    public static $passwordField = '#password';
    public static $loginButton = "#app-login-form button:last-child";

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
    }

    public function login($username, $password, $autoStart = true)
    {
        $I = $this->tester;

        if ($I->loadSessionSnapshot($this->getSessionName($username))) {
            return $this;
        }

        $I->amOnPage(self::$URL);
        $I->fillField(self::$usernameField, $username);
        $I->fillField(self::$passwordField, $password);
        $I->click(self::$loginButton);

        if ($I->getCurrentUrl() == '/start/' && $autoStart) {
            $I->click(Start::$agreeCheckbox);
            $I->click(Start::$submitButton);
        }

        $this->saveLoginSession($username);

        return $this;
    }

    public function loginAsAdmin($autoStart = true)
    {
        $I = $this->tester;

        return $this->login($I::ADMIN_LOGIN, $I::ADMIN_PASSWORD, $autoStart);
    }

    public function loginAsUser($autoStart = true)
    {
        $I = $this->tester;

        return $this->login($I::USER_LOGIN, $I::USER_PASSWORD, $autoStart);
    }

    public function saveLoginSession($username)
    {
        $I = $this->tester;

        $I->saveSessionSnapshot($this->getSessionName($username));

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

    protected function getSessionName($username)
    {
        return $this->getCalledCest() . '_' . $username;
    }

}
