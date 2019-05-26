<?php

/**
 * Class mainPageCest
 * Проверка доступности главной страницы сайта и того что не ней отображается
 */

class mainPageCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->wait(1);
        $I->amOnPage('/');
    }

    // tests
    public function mainPageTest(AcceptanceTester $I)
    {
        $I->see("Анализ ПДВ");
        $I->click(" Поиск данных");
        $I->seeLink("в ЕРПН","/searchERPN");
        $I->seeLink("в Реестрах","/searchReestr");
        $I->see("Остальное");
    }
}
