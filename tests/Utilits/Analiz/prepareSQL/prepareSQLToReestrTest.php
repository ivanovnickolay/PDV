<?php

namespace App\Utilits\Analiz\prepareSQL;

use App\Utilits\Analiz\Exception\noCorrectRoutingSearchException;
use PHPUnit\Framework\TestCase;

class prepareSQLToReestrTest extends TestCase
{
    /**
     * Тестирование генерации исключения при вводе не верного направления поиска
    * @throws noCorrectRoutingSearchException
     */
    public function testException_noCorrectRouting()
    {
        $this->expectException(noCorrectRoutingSearchException::class);
        prepareSQLToReestr::getPrepareSQL("fsfsf");
    }

    /**
     * Тест правильности возврата текста запросов путем контроля уникальных фрагментов запросов
     * @throws noCorrectRoutingSearchException
     */
    public function testGetPrepareSQL()
    {
        $this->assertNotFalse(strpos(
            prepareSQLToReestr::getPrepareSQL("ReestrIn"),
            '  FROM reestrbranch_in
                 WHERE month = :m
                  AND year = :y'
        ));
        $this->assertNotFalse(strpos(
            prepareSQLToReestr::getPrepareSQL("ReestrOut"),
            '  FROM reestrbranch_out
                  WHERE month = :m
                  AND year = :y;'
        ));
    }

}
